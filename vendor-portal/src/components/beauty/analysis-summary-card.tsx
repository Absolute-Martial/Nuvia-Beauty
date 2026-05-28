type AnalysisSummaryCardProps = {
  analysisResult: {
    summary: {
      analysis_mode?: string;
      provider?: string;
      demo_mode?: boolean;
      message?: string;
      skin_type?: string;
      tone?: string;
      undertone?: string;
      key_concerns?: string[];
      observations?: string[];
    };
    normalized_traits: {
      skin_type_tags: string[];
      tone_tags: string[];
      undertone_tags: string[];
      concern_tags: string[];
      ingredient_tags: string[];
      avoid_tags: string[];
    };
    recommendation_count: number;
    completed_at?: string | null;
  } | null;
};

const renderTags = (values: string[]) =>
  values.length ? values.join(', ') : 'None captured';

export default function AnalysisSummaryCard({ analysisResult }: AnalysisSummaryCardProps) {
  if (!analysisResult) {
    return null;
  }

  const { summary, normalized_traits: traits } = analysisResult;

  return (
    <div className="rounded border border-border-base bg-light p-5">
      <div className="flex items-center justify-between gap-3">
        <div>
          <h3 className="text-sm font-semibold text-heading">Normalized analysis summary</h3>
          <p className="mt-1 text-xs text-body">
            {summary.demo_mode ? 'Demo mode' : 'Live mode'} · {summary.provider ?? 'perfect_corp'}
          </p>
        </div>
        <span className="rounded-full bg-gray-100 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-gray-700">
          {summary.analysis_mode ?? 'analysis'}
        </span>
      </div>

      <p className="mt-4 text-sm text-body">{summary.message ?? 'Analysis completed.'}</p>

      <div className="mt-4 grid grid-cols-1 gap-3 md:grid-cols-2">
        <p className="text-sm text-body"><span className="font-semibold text-heading">Skin type:</span> {summary.skin_type ?? 'Unknown'}</p>
        <p className="text-sm text-body"><span className="font-semibold text-heading">Tone:</span> {summary.tone ?? 'Unknown'}</p>
        <p className="text-sm text-body"><span className="font-semibold text-heading">Undertone:</span> {summary.undertone ?? 'Unknown'}</p>
        <p className="text-sm text-body"><span className="font-semibold text-heading">Recommendations:</span> {analysisResult.recommendation_count}</p>
      </div>

      <div className="mt-4 space-y-2 text-sm text-body">
        <p><span className="font-semibold text-heading">Key concerns:</span> {(summary.key_concerns ?? []).join(', ') || 'None surfaced'}</p>
        <p><span className="font-semibold text-heading">Normalized skin types:</span> {renderTags(traits.skin_type_tags)}</p>
        <p><span className="font-semibold text-heading">Normalized tones:</span> {renderTags(traits.tone_tags)}</p>
        <p><span className="font-semibold text-heading">Normalized undertones:</span> {renderTags(traits.undertone_tags)}</p>
        <p><span className="font-semibold text-heading">Normalized concerns:</span> {renderTags(traits.concern_tags)}</p>
        <p><span className="font-semibold text-heading">Preferred ingredients:</span> {renderTags(traits.ingredient_tags)}</p>
        <p><span className="font-semibold text-heading">Avoid tags:</span> {renderTags(traits.avoid_tags)}</p>
      </div>

      {(summary.observations ?? []).length ? (
        <div className="mt-4 rounded bg-gray-50 p-4 text-sm text-body">
          <p className="font-semibold text-heading">Observations</p>
          <ul className="mt-2 list-disc space-y-1 pl-5">
            {(summary.observations ?? []).map((observation) => (
              <li key={observation}>{observation}</li>
            ))}
          </ul>
        </div>
      ) : null}
    </div>
  );
}
