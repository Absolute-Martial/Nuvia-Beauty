import { StaffPaginator, StaffQueryOptions } from '@/types';
import { useMutation, useQuery, useQueryClient } from 'react-query';
import { mapPaginatorData } from '@/utils/data-mappers';
import { API_ENDPOINTS } from './client/api-endpoints';
import { staffClient } from './client/staff';
import { useRouter } from 'next/router';
import { useTranslation } from 'next-i18next/pages';
import { toast } from 'react-toastify';
import { Routes } from '@/config/routes';

export const useStaffsQuery = (
  params: Partial<StaffQueryOptions>,
  options: any = {}
) => {
  const { data, error, isLoading } = useQuery<StaffPaginator, Error>(
    [API_ENDPOINTS.STAFFS, params],
    ({ queryKey, pageParam }) =>
      staffClient.paginated(Object.assign({}, queryKey[1], pageParam)),
    {
      keepPreviousData: true,
      ...options,
    }
  );
  return {
    staffs: data?.data ?? [],
    paginatorInfo: mapPaginatorData(data),
    error,
    loading: isLoading,
  };
};

export const useAddStaffMutation = () => {
  const queryClient = useQueryClient();
  const router = useRouter();
  const { t } = useTranslation();

  return useMutation(staffClient.addStaff, {
    onSuccess: () => {
      if (typeof window !== 'undefined' && (window as any).pendo) {
        (window as any).pendo.track('staff_member_added', {
          shop_slug: String(router?.query?.shop ?? ''),
        });
      }
      router.push(`/${router?.query?.shop}${Routes.staff.list}`);
      toast.success(t('common:successfully-created'));
    },
    // Always refetch after error or success:
    onSettled: () => {
      queryClient.invalidateQueries(API_ENDPOINTS.STAFFS);
    },
  });
};

export const useRemoveStaffMutation = () => {
  const queryClient = useQueryClient();
  const { t } = useTranslation();

  return useMutation(staffClient.removeStaff, {
    onSuccess: (data: any, variables: any) => {
      toast.success(t('common:successfully-deleted'));
      if (typeof window !== 'undefined' && (window as any).pendo) {
        (window as any).pendo.track('staff_member_removed', {
          staff_id: String(variables?.id ?? ''),
        });
      }
    },
    // Always refetch after error or success:
    onSettled: () => {
      queryClient.invalidateQueries(API_ENDPOINTS.STAFFS);
    },
  });
};
