import Badge from '@/components/ui/badge/badge';

type BeautyMappingStatus =
  | 'missing_mapping'
  | 'partial_mapping'
  | 'ready_for_recommendation';

const statusMap: Record<
  BeautyMappingStatus,
  { label: string; color: string }
> = {
  missing_mapping: {
    label: 'Missing mapping',
    color: 'bg-yellow-500',
  },
  partial_mapping: {
    label: 'Partial mapping',
    color: 'bg-orange-500',
  },
  ready_for_recommendation: {
    label: 'Ready for recommendation',
    color: 'bg-accent',
  },
};

export function getBeautyMappingStatus(
  dimensionCount: number,
  hasMapping: boolean,
): BeautyMappingStatus {
  if (!hasMapping) {
    return 'missing_mapping';
  }

  return dimensionCount >= 2
    ? 'ready_for_recommendation'
    : 'partial_mapping';
}

export default function BeautyMappingStatusBadge({
  status,
}: {
  status: BeautyMappingStatus;
}) {
  const meta = statusMap[status];

  return <Badge text={meta.label} color={meta.color} />;
}
