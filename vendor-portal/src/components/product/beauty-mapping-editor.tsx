import Alert from '@/components/ui/alert';
import Button from '@/components/ui/button';
import Card from '@/components/common/card';
import Input from '@/components/ui/input';
import TextArea from '@/components/ui/text-area';
import Badge from '@/components/ui/badge/badge';
import { Product } from '@/types';
import {
  useBeautyProductMappingQuery,
  useUpsertBeautyProductMappingMutation,
} from '@/data/beauty-product-mapping';
import { type ChangeEvent, useEffect, useState } from 'react';

type BeautyMappingEditorProps = {
  product?: Product | null;
};

type BeautyMappingDraft = {
  concern_tags: string;
  skin_type_tags: string;
  tone_tags: string;
  undertone_tags: string;
  ingredient_tags: string;
  avoid_tags: string;
  explanation_template: string;
};

const emptyDraft: BeautyMappingDraft = {
  concern_tags: '',
  skin_type_tags: '',
  tone_tags: '',
  undertone_tags: '',
  ingredient_tags: '',
  avoid_tags: '',
  explanation_template: '',
};

const toCsv = (values?: string[]) => (values && values.length ? values.join(', ') : '');

const parseCsv = (value: string) =>
  Array.from(
    new Set(
      value
        .split(',')
        .map((entry) => entry.trim())
        .filter(Boolean),
    ),
  );

export default function BeautyMappingEditor({
  product,
}: BeautyMappingEditorProps) {
  const productId = product?.id ? Number(product.id) : null;
  const shopId = product?.shop_id ? Number(product.shop_id) : undefined;
  const { mapping, isLoading, error } = useBeautyProductMappingQuery(
    productId ?? undefined,
    shopId,
  );
  const { mutate: upsertMapping, isLoading: saving } =
    useUpsertBeautyProductMappingMutation();
  const [draft, setDraft] = useState<BeautyMappingDraft>(emptyDraft);

  useEffect(() => {
    if (!mapping) {
      setDraft(emptyDraft);
      return;
    }

    setDraft({
      concern_tags: toCsv(mapping.concern_tags),
      skin_type_tags: toCsv(mapping.skin_type_tags),
      tone_tags: toCsv(mapping.tone_tags),
      undertone_tags: toCsv(mapping.undertone_tags),
      ingredient_tags: toCsv(mapping.ingredient_tags),
      avoid_tags: toCsv(mapping.avoid_tags),
      explanation_template: mapping.explanation_template ?? '',
    });
  }, [mapping]);

  if (!productId) {
    return (
      <Card className="w-full sm:w-8/12 md:w-2/3">
        <Alert message="Save the product first, then attach beauty mapping metadata." />
      </Card>
    );
  }

  const handleChange =
    (field: keyof BeautyMappingDraft) =>
    (event: ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
      setDraft((current) => ({
        ...current,
        [field]: event.target.value,
      }));
    };

  const handleSave = () => {
    upsertMapping({
      mappingId: mapping?.id,
      payload: {
        product_id: productId,
        concern_tags: parseCsv(draft.concern_tags),
        skin_type_tags: parseCsv(draft.skin_type_tags),
        tone_tags: parseCsv(draft.tone_tags),
        undertone_tags: parseCsv(draft.undertone_tags),
        ingredient_tags: parseCsv(draft.ingredient_tags),
        avoid_tags: parseCsv(draft.avoid_tags),
        explanation_template: draft.explanation_template.trim(),
      },
    });
  };

  return (
    <Card className="w-full sm:w-8/12 md:w-2/3">
      <div className="mb-5 flex flex-wrap items-center gap-3">
        <Badge
          text={mapping ? 'Mapped' : 'Not mapped'}
          color={mapping ? 'bg-accent' : 'bg-yellow-500'}
        />
        <span className="text-sm text-body">
          Product ID: {productId}
        </span>
      </div>

      {error ? (
        <Alert
          variant="error"
          message="Failed to load beauty mapping data."
          className="mb-5"
        />
      ) : null}

      <div className="grid grid-cols-1 gap-5 md:grid-cols-2">
        <Input
          name="beauty-concern-tags"
          label="Concern tags"
          value={draft.concern_tags}
          onChange={handleChange('concern_tags')}
          note="Comma-separated tags, e.g. dark_spot, acne"
          disabled={isLoading}
        />
        <Input
          name="beauty-skin-type-tags"
          label="Skin type tags"
          value={draft.skin_type_tags}
          onChange={handleChange('skin_type_tags')}
          note="Comma-separated tags, e.g. oily, combination"
          disabled={isLoading}
        />
        <Input
          name="beauty-tone-tags"
          label="Tone tags"
          value={draft.tone_tags}
          onChange={handleChange('tone_tags')}
          note="Comma-separated tags, e.g. fair, medium"
          disabled={isLoading}
        />
        <Input
          name="beauty-undertone-tags"
          label="Undertone tags"
          value={draft.undertone_tags}
          onChange={handleChange('undertone_tags')}
          note="Comma-separated tags, e.g. warm, neutral"
          disabled={isLoading}
        />
        <Input
          name="beauty-ingredient-tags"
          label="Ingredient tags"
          value={draft.ingredient_tags}
          onChange={handleChange('ingredient_tags')}
          note="Comma-separated tags, e.g. niacinamide, ceramide"
          disabled={isLoading}
        />
        <Input
          name="beauty-avoid-tags"
          label="Avoid tags"
          value={draft.avoid_tags}
          onChange={handleChange('avoid_tags')}
          note="Comma-separated tags, e.g. fragrance, alcohol"
          disabled={isLoading}
        />
      </div>

      <div className="mt-5">
        <TextArea
          name="beauty-explanation-template"
          label="Explanation template"
          value={draft.explanation_template}
          onChange={handleChange('explanation_template')}
          variant="outline"
          disabled={isLoading}
        />
      </div>

      <div className="mt-6 flex flex-wrap items-center gap-3">
        <Button
          type="button"
          loading={saving}
          disabled={saving}
          onClick={handleSave}
        >
          Save beauty mapping
        </Button>
      </div>
    </Card>
  );
}
