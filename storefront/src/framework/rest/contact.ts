import { useMutation } from "react-query";
import { toast } from "react-toastify";
import { useTranslation } from "next-i18next/pages";
import client from '@framework/utils/index'

export const useContact = () => {
  const { t } = useTranslation('common');
  return useMutation(client.contact.create, {
    onSuccess: (data, variables: any) => {
      if (data.success) {
        toast.success(t(data.message));
        if (typeof window !== 'undefined' && (window as any).pendo) {
          (window as any).pendo.track('contact_form_submitted', {
            subject: variables?.subject || '',
            success: 'true',
          });
        }
      } else {
        toast.error(t(data.message));
      }
    },
    onError: (error) => {
      const {
        response: { data },
      }: any = error ?? {};

      toast.error(t(data?.message));
    },
  });
};