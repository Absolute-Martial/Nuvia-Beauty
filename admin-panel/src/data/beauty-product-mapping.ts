import { useMutation, useQuery, useQueryClient } from 'react-query';
import { toast } from 'react-toastify';
import { useTranslation } from 'next-i18next/pages';
import { API_ENDPOINTS } from './client/api-endpoints';
import {
  BeautyMappingOverviewProduct,
  BeautyMappingOverviewSummary,
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

export const useRecomputeBeautySignalsMutation = () => {
  const { t } = useTranslation();

  return useMutation(
    ({ productIds }: { productIds?: number[] }) =>
      beautyProductMappingClient.recompute(productIds),
    {
      onSuccess: (response) => {
        const count =
          response?.data?.product_signal_recompute?.recomputed_count ?? 0;
        toast.success(
          t(`Recomputed product signals for ${count} product(s).`),
        );
      },
      onError: (error: any) => {
        toast.error(error?.response?.data?.message ?? t('Signal recompute failed.'));
      },
    },
  );
};

export const useBeautyProductMappingOverviewQuery = (params: {
  name?: string;
  page?: number;
  limit?: number;
  shop_id?: number | string;
}) => {
  const { data, error, isLoading, isFetching } = useQuery(
    [API_ENDPOINTS.ADMIN_BEAUTY_PRODUCT_MAPPINGS_OVERVIEW, params],
    () => beautyProductMappingClient.overview(params),
    {
      keepPreviousData: true,
    },
  );

  return {
    products:
      (data?.data?.products?.data as BeautyMappingOverviewProduct[] | undefined) ?? [],
    summary:
      (data?.data?.summary as BeautyMappingOverviewSummary | undefined) ?? null,
    paginatorInfo: data?.data?.products ?? null,
    error,
    isLoading,
    isFetching,
  };
};
