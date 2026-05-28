import { useMutation, useQuery, useQueryClient } from 'react-query';
import { toast } from 'react-toastify';
import { useTranslation } from 'next-i18next/pages';
import { API_ENDPOINTS } from './client/api-endpoints';
import {
  beautyProductMappingClient,
  BeautyProductMappingPayload,
} from './client/beauty-product-mapping';

export const useBeautyProductMappingQuery = (
  productId?: number,
  shopId?: number | string,
) => {
  const { data, error, isLoading } = useQuery(
    [API_ENDPOINTS.BEAUTY_PRODUCT_MAPPINGS, productId, shopId],
    () => beautyProductMappingClient.listByProduct(productId!, shopId),
    {
      enabled: Boolean(productId),
    },
  );

  return {
    mapping: data?.data?.data?.[0] ?? null,
    error,
    isLoading,
  };
};

export const useUpsertBeautyProductMappingMutation = () => {
  const { t } = useTranslation();
  const queryClient = useQueryClient();

  return useMutation(
    ({
      mappingId,
      payload,
    }: {
      mappingId?: number;
      payload: BeautyProductMappingPayload;
    }) =>
      mappingId
        ? beautyProductMappingClient.update(mappingId, payload)
        : beautyProductMappingClient.create(payload),
    {
      onSuccess: () => {
        toast.success(t('Beauty mapping saved.'));
      },
      onSettled: () => {
        queryClient.invalidateQueries(API_ENDPOINTS.BEAUTY_PRODUCT_MAPPINGS);
      },
      onError: (error: any) => {
        toast.error(error?.response?.data?.message ?? t('Beauty mapping update failed.'));
      },
    },
  );
};
