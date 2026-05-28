import { useMutation, useQuery, useQueryClient } from 'react-query';
import { toast } from 'react-toastify';
import { useTranslation } from 'next-i18next/pages';
import { API_ENDPOINTS } from './client/api-endpoints';
import {
  BeautyAnalysisStatusPayload,
  beautyConsultationClient,
  CreateBeautySessionPayload,
} from './client/beauty-consultation';

export const useCreateBeautySessionMutation = () => {
  const { t } = useTranslation();
  const queryClient = useQueryClient();

  return useMutation(
    (payload: CreateBeautySessionPayload) => beautyConsultationClient.createSession(payload),
    {
      onSuccess: () => {
        toast.success(t('Consultation session created.'));
      },
      onError: (error: any) => {
        toast.error(error?.response?.data?.message ?? t('Consultation creation failed.'));
      },
      onSettled: () => {
        queryClient.invalidateQueries(API_ENDPOINTS.BEAUTY_SESSIONS);
      },
    },
  );
};

export const useBeautySessionQuery = (sessionId?: string) => {
  const { data, error, isLoading, refetch } = useQuery(
    [API_ENDPOINTS.BEAUTY_SESSIONS, sessionId],
    () => beautyConsultationClient.getSession(sessionId!),
    {
      enabled: Boolean(sessionId),
    },
  );

  return {
    session: data?.data?.session ?? null,
    error,
    isLoading,
    refetch,
  };
};

export const useAttachBeautyMediaMutation = () => {
  const { t } = useTranslation();

  return useMutation(
    ({ sessionId, mediaAssetId }: { sessionId: string; mediaAssetId: number }) =>
      beautyConsultationClient.attachMedia(sessionId, mediaAssetId),
    {
      onSuccess: () => {
        toast.success(t('Consultation media attached.'));
      },
      onError: (error: any) => {
        toast.error(error?.response?.data?.message ?? t('Consultation media attach failed.'));
      },
    },
  );
};

export const useUploadBeautyMediaMutation = () => {
  const { t } = useTranslation();

  return useMutation(
    async ({
      sessionId,
      shopId,
      file,
    }: {
      sessionId: string;
      shopId: number;
      file: File;
    }) => {
      const slot = await beautyConsultationClient.createUploadSlot({
        purpose: 'beauty_input',
        asset_type: 'consultation_input_image',
        owner_type: 'shop',
        owner_id: shopId,
        shop_id: shopId,
        session_id: sessionId,
        file_name: file.name,
        content_type: file.type,
        size_bytes: file.size,
      });

      const uploadResponse = await fetch(slot.data.upload_url, {
        method: slot.data.method,
        headers: slot.data.headers,
        body: file,
      });

      if (!uploadResponse.ok) {
        throw new Error(`Upload failed with status ${uploadResponse.status}`);
      }

      await beautyConsultationClient.confirmMedia(slot.data.media_id);

      return beautyConsultationClient.attachMedia(sessionId, slot.data.media_id);
    },
    {
      onSuccess: () => {
        toast.success(t('Consultation image uploaded and attached.'));
      },
      onError: (error: any) => {
        toast.error(error?.message ?? error?.response?.data?.message ?? t('Consultation upload failed.'));
      },
    },
  );
};

export const useBeautyRecommendationsMutation = () => {
  const { t } = useTranslation();

  return useMutation(
    ({ sessionId, limit }: { sessionId: string; limit?: number }) =>
      beautyConsultationClient.getRecommendations(sessionId, { limit }),
    {
      onError: (error: any) => {
        toast.error(error?.response?.data?.message ?? t('Recommendation load failed.'));
      },
    },
  );
};

export const useStartBeautyAnalysisMutation = () => {
  const { t } = useTranslation();

  return useMutation(
    ({ sessionId }: { sessionId: string }) =>
      beautyConsultationClient.startAnalysis(sessionId),
    {
      onSuccess: () => {
        toast.success(t('Consultation analysis started.'));
      },
      onError: (error: any) => {
        toast.error(error?.response?.data?.message ?? t('Consultation analysis start failed.'));
      },
    },
  );
};

export const useBeautyAnalysisStatusQuery = (
  taskId?: number | null,
  enabled = true,
) => {
  const query = useQuery<BeautyAnalysisStatusPayload>(
    [API_ENDPOINTS.BEAUTY_ANALYSIS, taskId],
    () => beautyConsultationClient.getAnalysisStatus(taskId!),
    {
      enabled: Boolean(taskId) && enabled,
      refetchInterval: (response) => {
        const task = response?.data?.task;

        if (!task) {
          return false;
        }

        return ['queued', 'processing'].includes(task.status) ? 2000 : false;
      },
    },
  );

  return {
    ...query,
    analysisStatus: query.data?.data ?? null,
  };
};

export const useSaveBeautySessionMutation = () => {
  const { t } = useTranslation();

  return useMutation(
    ({ sessionId, notes }: { sessionId: string; notes?: string }) =>
      beautyConsultationClient.saveSession(sessionId, { notes }),
    {
      onSuccess: () => {
        toast.success(t('Consultation session saved.'));
      },
      onError: (error: any) => {
        toast.error(error?.response?.data?.message ?? t('Consultation save failed.'));
      },
    },
  );
};

export const useDiscardBeautySessionMutation = () => {
  const { t } = useTranslation();

  return useMutation(
    ({ sessionId, discardReason }: { sessionId: string; discardReason?: string }) =>
      beautyConsultationClient.discardSession(sessionId, { discard_reason: discardReason }),
    {
      onSuccess: () => {
        toast.success(t('Consultation session discarded.'));
      },
      onError: (error: any) => {
        toast.error(error?.response?.data?.message ?? t('Consultation discard failed.'));
      },
    },
  );
};
