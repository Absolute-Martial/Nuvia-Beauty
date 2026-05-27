type Props = {
  score: number;
  confidence: 'low' | 'medium' | 'high';
};

const confidenceClasses = {
  high: 'bg-emerald-100 text-emerald-700 border-emerald-200',
  medium: 'bg-amber-100 text-amber-700 border-amber-200',
  low: 'bg-rose-100 text-rose-700 border-rose-200',
};

export default function RecommendationScoreBadge({ score, confidence }: Props) {
  return (
    <div
      className={`inline-flex items-center gap-2 rounded-full border px-3 py-1 text-xs font-semibold uppercase tracking-wide ${confidenceClasses[confidence]}`}
    >
      <span>{confidence}</span>
      <span>{score}/100</span>
    </div>
  );
}
