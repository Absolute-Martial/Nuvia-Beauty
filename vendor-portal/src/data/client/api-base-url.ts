export const getApiBaseUrl = () =>
  typeof window !== 'undefined'
    ? '/api-backend'
    : process.env.INTERNAL_REST_API_ENDPOINT ||
      process.env.NEXT_PUBLIC_REST_API_ENDPOINT ||
      'http://127.0.0.1:8000';

export const getApiUrl = (path: string) => {
  const normalizedPath = path.replace(/^\/+/, '');

  if (typeof window !== 'undefined') {
    return `/api-backend/${normalizedPath}`;
  }

  return `${getApiBaseUrl().replace(/\/+$/, '')}/${normalizedPath}`;
};
