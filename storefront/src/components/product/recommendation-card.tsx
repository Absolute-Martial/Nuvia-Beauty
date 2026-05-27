import Image from 'next/image';
import Link from '@components/ui/link';
import { ROUTES } from '@lib/routes';
import { BeautyRecommendationItem } from '@type/index';
import RecommendationReasonList from './recommendation-reason-list';
import RecommendationScoreBadge from './recommendation-score-badge';
import RecommendationWarningList from './recommendation-warning-list';

type Props = {
  recommendation: BeautyRecommendationItem;
};

export default function RecommendationCard({ recommendation }: Props) {
  const product = recommendation.product;

  return (
    <article className="rounded-2xl border border-gray-200 bg-white p-4 shadow-sm">
      <div className="flex items-start justify-between gap-4">
        <div className="min-w-0">
          <p className="text-xs font-medium uppercase tracking-[0.18em] text-gray-500">
            Beauty match
          </p>
          <h3 className="mt-1 text-base font-semibold text-heading">
            {product?.name ?? `Product #${recommendation.product_id}`}
          </h3>
        </div>
        <RecommendationScoreBadge
          score={recommendation.score}
          confidence={recommendation.confidence}
        />
      </div>

      <div className="mt-4 flex gap-4">
        <div className="relative h-20 w-20 shrink-0 overflow-hidden rounded-xl bg-gray-100">
          {product?.image?.original ? (
            <Image
              fill
              src={product.image.original}
              alt={product?.name ?? 'Recommended product'}
              className="object-cover"
            />
          ) : null}
        </div>
        <div className="flex-1 space-y-4">
          <RecommendationReasonList reasons={recommendation.reasons} />
          <RecommendationWarningList warnings={recommendation.warnings} />
        </div>
      </div>

      {product?.slug ? (
        <div className="mt-4">
          <Link
            href={`${ROUTES.PRODUCT}/${product.slug}`}
            className="text-sm font-semibold text-accent transition hover:underline"
          >
            View recommended product
          </Link>
        </div>
      ) : null}
    </article>
  );
}
