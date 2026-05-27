type Props = {
  warnings: string[];
};

export default function RecommendationWarningList({ warnings }: Props) {
  if (!warnings.length) {
    return null;
  }

  return (
    <div className="space-y-2">
      <p className="text-sm font-semibold text-heading">Warnings</p>
      <ul className="space-y-1 text-sm text-red-700">
        {warnings.map((warning) => (
          <li
            key={warning}
            className="rounded-lg border border-red-200 bg-red-50 px-3 py-2"
          >
            {warning}
          </li>
        ))}
      </ul>
    </div>
  );
}
