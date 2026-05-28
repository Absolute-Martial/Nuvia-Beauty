import Card from '@/components/common/card';
import { BeautyConsultationRecommendation } from '@/data/client/beauty-consultation';

export default function RecommendationCard({
  recommendation,
}: {
  recommendation: BeautyConsultationRecommendation;
}) {
  return (
    <Card className="h-full">
      <div className="flex items-start justify-between gap-4">
        <div>
          <h3 className="text-base font-semibold text-heading">
            {recommendation.product.name}
          </h3>
          <p className="mt-1 text-xs text-body">Product #{recommendation.product_id}</p>
        </div>
        <div className="text-right">
          <p className="text-lg font-semibold text-accent">{recommendation.score}</p>
          <p className="text-xs uppercase tracking-wide text-body">
            {recommendation.confidence}
          </p>
        </div>
      </div>

      <div className="mt-4 space-y-4">
        <div>
          <p className="text-xs font-semibold uppercase tracking-wide text-body">
            Reasons
          </p>
          {recommendation.reasons.length ? (
            <ul className="mt-2 list-disc space-y-1 pl-5 text-sm text-body">
              {recommendation.reasons.map((reason) => (
                <li key={reason}>{reason}</li>
              ))}
            </ul>
          ) : (
            <p className="mt-2 text-sm text-body">No matching reasons were generated.</p>
          )}
        </div>

        <div>
          <p className="text-xs font-semibold uppercase tracking-wide text-body">
            Warnings
          </p>
          {recommendation.warnings.length ? (
            <ul className="mt-2 list-disc space-y-1 pl-5 text-sm text-rose-600">
              {recommendation.warnings.map((warning) => (
                <li key={warning}>{warning}</li>
              ))}
            </ul>
          ) : (
            <p className="mt-2 text-sm text-body">No warnings for this recommendation.</p>
          )}
        </div>
      </div>
    </Card>
  );
}
