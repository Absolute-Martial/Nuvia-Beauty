import Button from '@components/ui/button';
import { useBeautyRecommendations } from '@framework/beauty-recommendations';
import { Product } from '@type/index';
import { FormEvent, useMemo, useState } from 'react';
import RecommendationCard from './recommendation-card';

const skinTypeOptions = ['oily', 'dry', 'combination', 'normal', 'sensitive'];
const toneOptions = ['fair', 'light', 'medium', 'tan', 'deep'];
const undertoneOptions = ['cool', 'neutral', 'warm', 'olive'];

type Props = {
  product: Product;
};

export default function BeautyRecommendationPanel({ product }: Props) {
  const [skinType, setSkinType] = useState('oily');
  const [tone, setTone] = useState('medium');
  const [undertone, setUndertone] = useState('neutral');
  const [concerns, setConcerns] = useState('dark-spot,texture');
  const [ingredients, setIngredients] = useState('niacinamide');
  const [avoidTags, setAvoidTags] = useState('fragrance');
  const { mutate, data, isLoading, error, reset } = useBeautyRecommendations();

  const sessionId = useMemo(() => {
    if (typeof window === 'undefined') {
      return 'server-session';
    }

    const key = 'nuvia-beauty-session-id';
    const existing = window.localStorage.getItem(key);

    if (existing) {
      return existing;
    }

    const generated = window.crypto?.randomUUID?.() ?? `${Date.now()}`;
    window.localStorage.setItem(key, generated);
    return generated;
  }, []);

  function splitTags(value: string): string[] {
    return value
      .split(',')
      .map((item) => item.trim())
      .filter(Boolean);
  }

  function handleSubmit(event: FormEvent<HTMLFormElement>) {
    event.preventDefault();
    reset();
    mutate({
      session_id: sessionId,
      skin_type_tags: [skinType],
      tone_tags: [tone],
      undertone_tags: [undertone],
      concern_tags: splitTags(concerns),
      ingredient_tags: splitTags(ingredients),
      avoid_tags: splitTags(avoidTags),
      limit: 4,
    });
  }

  const recommendations = data?.data?.recommendations ?? [];

  return (
    <section className="mt-10 rounded-3xl border border-gray-200 bg-gray-50 p-6">
      <div className="mb-6">
        <p className="text-xs font-semibold uppercase tracking-[0.2em] text-gray-500">
          Phase 4 beauty intelligence
        </p>
        <h2 className="mt-2 text-2xl font-bold text-heading">
          Generate explainable product recommendations
        </h2>
        <p className="mt-2 max-w-2xl text-sm text-body">
          Use a deterministic skin-profile match to compare this catalog against your
          selected beauty concerns. Results show score, reasons, warnings, and
          confidence without exposing storage or provider secrets.
        </p>
      </div>

      <form className="grid gap-4 md:grid-cols-2" onSubmit={handleSubmit}>
        <label className="space-y-2 text-sm font-medium text-heading">
          Skin type
          <select
            className="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm"
            value={skinType}
            onChange={(event) => setSkinType(event.target.value)}
          >
            {skinTypeOptions.map((option) => (
              <option key={option} value={option}>
                {option}
              </option>
            ))}
          </select>
        </label>

        <label className="space-y-2 text-sm font-medium text-heading">
          Tone
          <select
            className="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm"
            value={tone}
            onChange={(event) => setTone(event.target.value)}
          >
            {toneOptions.map((option) => (
              <option key={option} value={option}>
                {option}
              </option>
            ))}
          </select>
        </label>

        <label className="space-y-2 text-sm font-medium text-heading">
          Undertone
          <select
            className="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm"
            value={undertone}
            onChange={(event) => setUndertone(event.target.value)}
          >
            {undertoneOptions.map((option) => (
              <option key={option} value={option}>
                {option}
              </option>
            ))}
          </select>
        </label>

        <label className="space-y-2 text-sm font-medium text-heading">
          Concern tags
          <input
            className="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm"
            value={concerns}
            onChange={(event) => setConcerns(event.target.value)}
            placeholder="dark-spot,texture,acne"
          />
        </label>

        <label className="space-y-2 text-sm font-medium text-heading">
          Ingredient tags
          <input
            className="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm"
            value={ingredients}
            onChange={(event) => setIngredients(event.target.value)}
            placeholder="niacinamide,hyaluronic-acid"
          />
        </label>

        <label className="space-y-2 text-sm font-medium text-heading">
          Avoid tags
          <input
            className="w-full rounded-xl border border-gray-300 bg-white px-4 py-3 text-sm"
            value={avoidTags}
            onChange={(event) => setAvoidTags(event.target.value)}
            placeholder="fragrance,retinol"
          />
        </label>

        <div className="md:col-span-2 flex items-center gap-3">
          <Button
            type="submit"
            loading={isLoading}
            className="rounded-xl px-6 py-3"
          >
            Generate recommendations
          </Button>
          <span className="text-sm text-body">
            Current product: {product.name}
          </span>
        </div>
      </form>

      {error ? (
        <div className="mt-6 rounded-2xl border border-red-200 bg-red-50 px-4 py-3 text-sm text-red-700">
          Recommendation request failed. Check the backend `/api/v1/beauty/recommendations/generate`
          response and retry.
        </div>
      ) : null}

      {!isLoading && data && recommendations.length === 0 ? (
        <div className="mt-6 rounded-2xl border border-gray-200 bg-white px-4 py-3 text-sm text-body">
          No mapped beauty recommendations are available yet for the current catalog.
        </div>
      ) : null}

      {recommendations.length > 0 ? (
        <div className="mt-6 grid gap-4">
          {recommendations.map((recommendation) => (
            <RecommendationCard
              key={`${recommendation.product_id}-${recommendation.score}`}
              recommendation={recommendation}
            />
          ))}
        </div>
      ) : null}
    </section>
  );
}
