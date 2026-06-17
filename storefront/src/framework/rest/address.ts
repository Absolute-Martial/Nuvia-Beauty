import { useMutation, useQueryClient } from 'react-query';
import { toast } from 'react-toastify';
import client from '@framework/utils/index'
import { useUI } from "@contexts/ui.context";
import { useTranslation } from 'next-i18next/pages';

export const useDeleteAddress = () => {
  const queryClient = useQueryClient();
  const { closeModal } = useUI();
  const { t } = useTranslation();

  return useMutation(client.address.deleteAddress, {
    onSuccess: (data: any, variables: any) => {
      toast.success(t('common:text-delete-success'));
      if (typeof window !== 'undefined' && (window as any).pendo) {
        (window as any).pendo.track('address_deleted', {
          address_id: String(variables?.id ?? ''),
        });
      }
      closeModal();
    },
    onError: (error) => {
      const {
        response: { data },
      }: any = error ?? {};

      toast.error(t(data?.message));
    },
    // Always refetch after error or success:
    onSettled: () => {
      queryClient.invalidateQueries('me');
    },
  });
};
