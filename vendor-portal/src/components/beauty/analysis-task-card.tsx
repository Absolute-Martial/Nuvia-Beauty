type AnalysisTaskCardProps = {
  task: {
    id: number;
    status: string;
    provider: string;
    queued_at?: string | null;
    started_at?: string | null;
    completed_at?: string | null;
    error_message?: string | null;
  } | null;
};

const toneByStatus: Record<string, string> = {
  queued: 'bg-yellow-100 text-yellow-800',
  processing: 'bg-blue-100 text-blue-800',
  completed: 'bg-green-100 text-green-800',
  failed: 'bg-red-100 text-red-800',
};

export default function AnalysisTaskCard({ task }: AnalysisTaskCardProps) {
  if (!task) {
    return null;
  }

  const tone = toneByStatus[task.status] ?? 'bg-gray-100 text-gray-800';

  return (
    <div className="rounded border border-border-base bg-light p-5">
      <div className="flex items-center justify-between gap-3">
        <div>
          <h3 className="text-sm font-semibold text-heading">Analysis task</h3>
          <p className="mt-1 text-xs text-body">Task #{task.id} via {task.provider}</p>
        </div>
        <span className={`rounded-full px-3 py-1 text-xs font-semibold uppercase tracking-wide ${tone}`}>
          {task.status}
        </span>
      </div>

      <div className="mt-4 space-y-2 text-sm text-body">
        <p>Queued: {task.queued_at ?? 'Not recorded yet'}</p>
        <p>Started: {task.started_at ?? 'Not started yet'}</p>
        <p>Completed: {task.completed_at ?? 'Waiting for completion'}</p>
        {task.error_message ? (
          <p className="font-medium text-red-600">Failure: {task.error_message}</p>
        ) : null}
      </div>
    </div>
  );
}
