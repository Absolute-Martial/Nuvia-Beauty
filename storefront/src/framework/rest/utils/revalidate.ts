const DEFAULT_REVALIDATE_DURATION = 120;

export function getRevalidateDuration() {
  const duration = Number(process.env.REVALIDATE_DURATION);

  return Number.isInteger(duration) && duration > 0
    ? duration
    : DEFAULT_REVALIDATE_DURATION;
}
