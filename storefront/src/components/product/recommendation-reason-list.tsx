type Props = {
  reasons: string[];
};

export default function RecommendationReasonList({ reasons }: Props) {
  if (!reasons.length) {
    return null;
  }

  return (
    <div className="space-y-2">
      <p className="text-sm font-semibold text-heading">Why it fits</p>
      <ul className="space-y-1 text-sm text-body">
        {reasons.map((reason) => (
          <li key={reason} className="flex items-start gap-2">
            <span className="mt-1.5 h-1.5 w-1.5 rounded-full bg-emerald-500" />
            <span>{reason}</span>
          </li>
        ))}
      </ul>
    </div>
  );
}
