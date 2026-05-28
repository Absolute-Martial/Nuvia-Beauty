type ConsultationStatusBadgeProps = {
  status: string;
};

const classes: Record<string, string> = {
  draft: 'bg-slate-100 text-slate-700',
  media_uploaded: 'bg-blue-100 text-blue-700',
  analysis_pending: 'bg-amber-100 text-amber-700',
  analysis_completed: 'bg-emerald-100 text-emerald-700',
  saved: 'bg-emerald-100 text-emerald-800',
  discarded: 'bg-rose-100 text-rose-700',
  failed: 'bg-rose-100 text-rose-700',
};

export default function ConsultationStatusBadge({
  status,
}: ConsultationStatusBadgeProps) {
  return (
    <span
      className={`inline-flex rounded-full px-3 py-1 text-xs font-semibold capitalize ${
        classes[status] ?? 'bg-gray-100 text-gray-700'
      }`}
    >
      {status.replaceAll('_', ' ')}
    </span>
  );
}
