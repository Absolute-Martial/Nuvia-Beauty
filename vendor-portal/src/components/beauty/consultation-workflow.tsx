import Alert from '@/components/ui/alert';
import AsyncSelect from 'react-select/async';
import Button from '@/components/ui/button';
import Card from '@/components/common/card';
import Input from '@/components/ui/input';
import Loader from '@/components/ui/loader/loader';
import PageHeading from '@/components/common/page-heading';
import TextArea from '@/components/ui/text-area';
import { selectStyles } from '@/components/ui/select/select.styles';
import RecommendationCard from './recommendation-card';
import ConsultationStatusBadge from './consultation-status-badge';
import { userClient } from '@/data/client/user';
import {
  BeautyConsultationSession,
  BeautyConsultationRecommendation,
} from '@/data/client/beauty-consultation';
import {
  useAttachBeautyMediaMutation,
  useBeautyRecommendationsMutation,
  useCreateBeautySessionMutation,
  useDiscardBeautySessionMutation,
  useSaveBeautySessionMutation,
  useUploadBeautyMediaMutation,
} from '@/data/beauty-consultation';
import { ChangeEvent, useMemo, useState } from 'react';

type ShopOption = {
  id: number;
  name: string;
  slug?: string;
};

type CustomerOption = {
  value: number;
  label: string;
  email?: string | null;
};

type ConsultationWorkflowProps = {
  shops: ShopOption[];
};

type ConsultationDraft = {
  shopId: number | null;
  consultationMode: 'guest' | 'new_customer' | 'returning_customer';
  customerName: string;
  contactEmail: string;
  contactPhone: string;
  notes: string;
  skinTypeTags: string;
  toneTags: string;
  undertoneTags: string;
  concernTags: string;
  ingredientTags: string;
  avoidTags: string;
};

const emptyDraft: ConsultationDraft = {
  shopId: null,
  consultationMode: 'guest',
  customerName: '',
  contactEmail: '',
  contactPhone: '',
  notes: '',
  skinTypeTags: '',
  toneTags: '',
  undertoneTags: '',
  concernTags: '',
  ingredientTags: '',
  avoidTags: '',
};

const parseCsv = (value: string) =>
  Array.from(
    new Set(
      value
        .split(',')
        .map((entry) => entry.trim())
        .filter(Boolean),
    ),
  );

export default function ConsultationWorkflow({
  shops,
}: ConsultationWorkflowProps) {
  const [draft, setDraft] = useState<ConsultationDraft>({
    ...emptyDraft,
    shopId: shops[0]?.id ?? null,
  });
  const [selectedCustomer, setSelectedCustomer] = useState<CustomerOption | null>(null);
  const [activeSession, setActiveSession] = useState<BeautyConsultationSession | null>(null);
  const [existingMediaId, setExistingMediaId] = useState('');
  const [selectedFile, setSelectedFile] = useState<File | null>(null);
  const [recommendations, setRecommendations] = useState<BeautyConsultationRecommendation[]>([]);
  const [inlineError, setInlineError] = useState<string | null>(null);

  const { mutateAsync: createSession, isLoading: creatingSession } =
    useCreateBeautySessionMutation();
  const { mutateAsync: attachMedia, isLoading: attachingMedia } =
    useAttachBeautyMediaMutation();
  const { mutateAsync: uploadMedia, isLoading: uploadingMedia } =
    useUploadBeautyMediaMutation();
  const { mutateAsync: loadRecommendations, isLoading: loadingRecommendations } =
    useBeautyRecommendationsMutation();
  const { mutateAsync: saveSession, isLoading: savingSession } =
    useSaveBeautySessionMutation();
  const { mutateAsync: discardSession, isLoading: discardingSession } =
    useDiscardBeautySessionMutation();

  const currentShop = useMemo(
    () => shops.find((shop) => shop.id === draft.shopId) ?? null,
    [draft.shopId, shops],
  );

  const isTerminalState =
    activeSession?.session_state === 'saved' ||
    activeSession?.session_state === 'discarded';

  async function loadCustomerOptions(inputValue: string) {
    const data = await userClient.fetchCustomers({ name: inputValue, page: 1, limit: 10 });
    return (data?.data ?? []).map((user: any) => ({
      value: Number(user.id),
      label: user.name,
      email: user.email,
    }));
  }

  const handleDraftChange =
    (field: keyof ConsultationDraft) =>
    (event: ChangeEvent<HTMLInputElement | HTMLTextAreaElement | HTMLSelectElement>) => {
      setDraft((current) => ({
        ...current,
        [field]:
          field === 'shopId' ? Number(event.target.value) || null : event.target.value,
      }));
    };

  const resetForNewSession = () => {
    setActiveSession(null);
    setRecommendations([]);
    setExistingMediaId('');
    setSelectedFile(null);
    setInlineError(null);
  };

  const handleCreateSession = async () => {
    setInlineError(null);

    if (!draft.shopId) {
      setInlineError('Select a shop before starting the consultation.');
      return;
    }

    if (draft.consultationMode === 'returning_customer' && !selectedCustomer) {
      setInlineError('Select a returning customer before starting the consultation.');
      return;
    }

    try {
      const response = await createSession({
        shop_id: draft.shopId,
        consultation_mode: draft.consultationMode,
        customer_id: selectedCustomer?.value,
        customer_name:
          draft.consultationMode === 'returning_customer'
            ? selectedCustomer?.label
            : draft.customerName || undefined,
        contact_email:
          draft.consultationMode === 'returning_customer'
            ? selectedCustomer?.email ?? undefined
            : draft.contactEmail || undefined,
        contact_phone: draft.contactPhone || undefined,
        notes: draft.notes || undefined,
        skin_type_tags: parseCsv(draft.skinTypeTags),
        tone_tags: parseCsv(draft.toneTags),
        undertone_tags: parseCsv(draft.undertoneTags),
        concern_tags: parseCsv(draft.concernTags),
        ingredient_tags: parseCsv(draft.ingredientTags),
        avoid_tags: parseCsv(draft.avoidTags),
      });

      setActiveSession(response.data.session);
      setRecommendations([]);
    } catch (error: any) {
      setInlineError(error?.response?.data?.message ?? 'Failed to create the consultation session.');
    }
  };

  const handleAttachExistingMedia = async () => {
    if (!activeSession || !existingMediaId) {
      setInlineError('Enter an existing media asset ID first.');
      return;
    }

    setInlineError(null);

    try {
      const response = await attachMedia({
        sessionId: activeSession.id,
        mediaAssetId: Number(existingMediaId),
      });
      setActiveSession(response.data.session);
    } catch (error: any) {
      setInlineError(error?.response?.data?.message ?? 'Failed to attach the existing media asset.');
    }
  };

  const handleUploadAndAttach = async () => {
    if (!activeSession || !selectedFile || !draft.shopId) {
      setInlineError('Create a session and choose a file before uploading.');
      return;
    }

    if (
      ![
        'image/jpeg',
        'image/png',
        'image/webp',
        'image/heic',
        'image/heif',
      ].includes(selectedFile.type)
    ) {
      setInlineError('Only JPEG, PNG, WEBP, HEIC, and HEIF files are allowed.');
      return;
    }

    setInlineError(null);

    try {
      const response = await uploadMedia({
        sessionId: activeSession.id,
        shopId: draft.shopId,
        file: selectedFile,
      });
      setActiveSession(response.data.session);
      setSelectedFile(null);
    } catch (error: any) {
      setInlineError(error?.message ?? 'Failed to upload and attach consultation media.');
    }
  };

  const handleLoadRecommendations = async () => {
    if (!activeSession) {
      setInlineError('Create a consultation session first.');
      return;
    }

    setInlineError(null);

    try {
      const response = await loadRecommendations({ sessionId: activeSession.id, limit: 8 });
      setRecommendations(response.data.recommendations ?? []);
      const refreshed = {
        ...activeSession,
        session_state:
          activeSession.session_state === 'saved'
            ? activeSession.session_state
            : 'analysis_completed',
      };
      setActiveSession(refreshed);
    } catch (error: any) {
      setInlineError(error?.response?.data?.message ?? 'Failed to load recommendations.');
    }
  };

  const handleSaveSession = async () => {
    if (!activeSession) {
      return;
    }

    setInlineError(null);

    try {
      const response = await saveSession({
        sessionId: activeSession.id,
        notes: draft.notes || undefined,
      });
      setActiveSession(response.data.session);
    } catch (error: any) {
      setInlineError(error?.response?.data?.message ?? 'Failed to save the consultation session.');
    }
  };

  const handleDiscardSession = async () => {
    if (!activeSession) {
      return;
    }

    setInlineError(null);

    try {
      const response = await discardSession({
        sessionId: activeSession.id,
        discardReason: draft.notes || undefined,
      });
      setActiveSession(response.data.session);
    } catch (error: any) {
      setInlineError(error?.response?.data?.message ?? 'Failed to discard the consultation session.');
    }
  };

  if (!shops.length) {
    return (
      <Alert message="No managed shop was found for this account. Beauty consultations require shop access." />
    );
  }

  return (
    <div className="space-y-8">
      <Card className="flex flex-col gap-4">
        <PageHeading title="Beauty consultations" />
        <p className="text-sm text-body">
          Start a seller-assisted consultation, attach a private input image through the Phase 4 storage flow, review deterministic recommendations, then save or discard the session.
        </p>
        {inlineError ? (
          <Alert variant="error" message={inlineError} />
        ) : null}
      </Card>

      <Card className="space-y-5">
        <div className="flex items-center justify-between gap-3">
          <div>
            <h2 className="text-base font-semibold text-heading">1. Session details</h2>
            <p className="mt-1 text-sm text-body">
              Capture the consultation mode and structured beauty profile inputs.
            </p>
          </div>
          {activeSession ? (
            <div className="flex items-center gap-3">
              <ConsultationStatusBadge status={activeSession.session_state} />
              <Button type="button" variant="outline" onClick={resetForNewSession}>
                Start another session
              </Button>
            </div>
          ) : null}
        </div>

        <div className="grid grid-cols-1 gap-5 md:grid-cols-2">
          <div>
            <label className="mb-2 block text-sm font-semibold text-body-dark">
              Shop
            </label>
            <select
              className="h-12 w-full rounded border border-border-base bg-light px-4 text-sm"
              value={draft.shopId ?? ''}
              onChange={handleDraftChange('shopId')}
              disabled={Boolean(activeSession)}
            >
              {shops.map((shop) => (
                <option key={shop.id} value={shop.id}>
                  {shop.name}
                </option>
              ))}
            </select>
          </div>

          <div>
            <label className="mb-2 block text-sm font-semibold text-body-dark">
              Consultation mode
            </label>
            <select
              className="h-12 w-full rounded border border-border-base bg-light px-4 text-sm"
              value={draft.consultationMode}
              onChange={handleDraftChange('consultationMode')}
              disabled={Boolean(activeSession)}
            >
              <option value="guest">Guest</option>
              <option value="new_customer">New customer</option>
              <option value="returning_customer">Returning customer</option>
            </select>
          </div>

          {draft.consultationMode === 'returning_customer' ? (
            <div className="md:col-span-2">
              <label className="mb-2 block text-sm font-semibold text-body-dark">
                Returning customer
              </label>
              <AsyncSelect
                styles={selectStyles}
                cacheOptions
                defaultOptions
                loadOptions={loadCustomerOptions}
                value={selectedCustomer}
                onChange={(option) => setSelectedCustomer(option as CustomerOption)}
                isDisabled={Boolean(activeSession)}
              />
            </div>
          ) : (
            <>
              <Input
                name="beauty-customer-name"
                label="Customer name"
                value={draft.customerName}
                onChange={handleDraftChange('customerName')}
                disabled={Boolean(activeSession)}
              />
              <Input
                name="beauty-contact-email"
                label="Customer email"
                value={draft.contactEmail}
                onChange={handleDraftChange('contactEmail')}
                disabled={Boolean(activeSession)}
              />
              <Input
                name="beauty-contact-phone"
                label="Customer phone"
                value={draft.contactPhone}
                onChange={handleDraftChange('contactPhone')}
                disabled={Boolean(activeSession)}
              />
            </>
          )}

          <Input
            name="beauty-skin-type-tags"
            label="Skin type tags"
            value={draft.skinTypeTags}
            onChange={handleDraftChange('skinTypeTags')}
            note="Comma-separated, for example oily, combination"
            disabled={Boolean(activeSession)}
          />
          <Input
            name="beauty-tone-tags"
            label="Tone tags"
            value={draft.toneTags}
            onChange={handleDraftChange('toneTags')}
            note="Comma-separated, for example fair, medium"
            disabled={Boolean(activeSession)}
          />
          <Input
            name="beauty-undertone-tags"
            label="Undertone tags"
            value={draft.undertoneTags}
            onChange={handleDraftChange('undertoneTags')}
            note="Comma-separated, for example warm, neutral"
            disabled={Boolean(activeSession)}
          />
          <Input
            name="beauty-concern-tags"
            label="Concern tags"
            value={draft.concernTags}
            onChange={handleDraftChange('concernTags')}
            note="Comma-separated, for example dark_spot, acne"
            disabled={Boolean(activeSession)}
          />
          <Input
            name="beauty-ingredient-tags"
            label="Ingredient tags"
            value={draft.ingredientTags}
            onChange={handleDraftChange('ingredientTags')}
            note="Comma-separated, for example niacinamide, ceramide"
            disabled={Boolean(activeSession)}
          />
          <Input
            name="beauty-avoid-tags"
            label="Avoid tags"
            value={draft.avoidTags}
            onChange={handleDraftChange('avoidTags')}
            note="Comma-separated, for example fragrance, alcohol"
            disabled={Boolean(activeSession)}
          />
        </div>

        <TextArea
          name="beauty-consultation-notes"
          label="Consultation notes"
          value={draft.notes}
          onChange={handleDraftChange('notes')}
          variant="outline"
          disabled={Boolean(activeSession)}
        />

        <div className="flex justify-end">
          <Button
            type="button"
            loading={creatingSession}
            disabled={creatingSession || Boolean(activeSession)}
            onClick={handleCreateSession}
          >
            Create consultation session
          </Button>
        </div>
      </Card>

      <Card className="space-y-5">
        <div>
          <h2 className="text-base font-semibold text-heading">2. Attach private media</h2>
          <p className="mt-1 text-sm text-body">
            Either attach an existing private media asset ID or upload a new consultation image through the presigned storage flow.
          </p>
        </div>

        {!activeSession ? (
          <Alert message="Create a consultation session before attaching media." />
        ) : (
          <>
            <div className="rounded border border-border-base bg-gray-50 p-4 text-sm text-body">
              <p>
                Session ID: <span className="font-semibold text-heading">{activeSession.id}</span>
              </p>
              <p className="mt-1">
                Shop: <span className="font-semibold text-heading">{currentShop?.name}</span>
              </p>
              {activeSession.media_asset ? (
                <p className="mt-1">
                  Attached media asset #{activeSession.media_asset.id} · {activeSession.media_asset.content_type}
                </p>
              ) : null}
            </div>

            <div className="grid grid-cols-1 gap-5 md:grid-cols-2">
              <Input
                name="beauty-existing-media-id"
                label="Existing media asset ID"
                value={existingMediaId}
                onChange={(event) => setExistingMediaId(event.target.value)}
                note="Use this when a private beauty input asset already exists."
                disabled={isTerminalState}
              />
              <div className="flex items-end">
                <Button
                  type="button"
                  loading={attachingMedia}
                  disabled={attachingMedia || isTerminalState}
                  onClick={handleAttachExistingMedia}
                >
                  Attach existing media
                </Button>
              </div>

              <div className="md:col-span-2">
                <label className="mb-2 block text-sm font-semibold text-body-dark">
                  Upload new consultation image
                </label>
                <input
                  type="file"
                  accept="image/jpeg,image/png,image/webp,image/heic,image/heif"
                  onChange={(event) => setSelectedFile(event.target.files?.[0] ?? null)}
                  disabled={isTerminalState}
                />
                {selectedFile ? (
                  <p className="mt-2 text-xs text-body">
                    Selected file: {selectedFile.name} ({Math.round(selectedFile.size / 1024)} KB)
                  </p>
                ) : null}
                <div className="mt-3">
                  <Button
                    type="button"
                    loading={uploadingMedia}
                    disabled={uploadingMedia || !selectedFile || isTerminalState}
                    onClick={handleUploadAndAttach}
                  >
                    Upload and attach image
                  </Button>
                </div>
              </div>
            </div>
          </>
        )}
      </Card>

      <Card className="space-y-5">
        <div className="flex items-center justify-between gap-3">
          <div>
            <h2 className="text-base font-semibold text-heading">3. Recommendations</h2>
            <p className="mt-1 text-sm text-body">
              Recommendations are generated from the stored consultation profile and existing beauty product mappings.
            </p>
          </div>
          <Button
            type="button"
            loading={loadingRecommendations}
            disabled={!activeSession || loadingRecommendations || isTerminalState}
            onClick={handleLoadRecommendations}
          >
            View recommendations
          </Button>
        </div>

        {!activeSession ? (
          <Alert message="Create a consultation session before generating recommendations." />
        ) : loadingRecommendations ? (
          <Loader text="Generating recommendations..." />
        ) : recommendations.length ? (
          <div className="grid grid-cols-1 gap-5 xl:grid-cols-2">
            {recommendations.map((recommendation) => (
              <RecommendationCard
                key={`${activeSession.id}-${recommendation.product_id}`}
                recommendation={recommendation}
              />
            ))}
          </div>
        ) : (
          <Alert message="No recommendations have been generated for this session yet." />
        )}
      </Card>

      <Card className="space-y-5">
        <div>
          <h2 className="text-base font-semibold text-heading">4. Save or discard</h2>
          <p className="mt-1 text-sm text-body">
            Save the consultation when the seller wants to keep the session record, or discard it to close the workflow.
          </p>
        </div>

        {!activeSession ? (
          <Alert message="Create a consultation session before saving or discarding it." />
        ) : (
          <div className="flex flex-wrap gap-3">
            <Button
              type="button"
              loading={savingSession}
              disabled={savingSession || isTerminalState}
              onClick={handleSaveSession}
            >
              Save consultation
            </Button>
            <Button
              type="button"
              variant="outline"
              loading={discardingSession}
              disabled={discardingSession || isTerminalState}
              onClick={handleDiscardSession}
            >
              Discard consultation
            </Button>
          </div>
        )}
      </Card>
    </div>
  );
}
