import Layout from '@/components/layouts/app';
import ConsultationWorkflow from '@/components/beauty/consultation-workflow';
import Loader from '@/components/ui/loader/loader';
import ErrorMessage from '@/components/ui/error-message';
import { useMeQuery } from '@/data/user';
import { adminOwnerAndStaffOnly } from '@/utils/auth-utils';
import { serverSideTranslations } from 'next-i18next/pages/serverSideTranslations';
import { useTranslation } from 'next-i18next/pages';

export default function BeautyConsultationsPage() {
  const { t } = useTranslation();
  const { data: me, isLoading, error } = useMeQuery();

  if (isLoading) {
    return <Loader text={t('common:text-loading')} />;
  }

  if (error) {
    return <ErrorMessage message={error.message} />;
  }

  const shopMap = new Map<number, { id: number; name: string; slug?: string }>();

  if (me?.managed_shop) {
    shopMap.set(Number(me.managed_shop.id), {
      id: Number(me.managed_shop.id),
      name: me.managed_shop.name,
      slug: me.managed_shop.slug,
    });
  }

  (me?.shops ?? []).forEach((shop) => {
    shopMap.set(Number(shop.id), {
      id: Number(shop.id),
      name: shop.name,
      slug: shop.slug,
    });
  });

  const shops = Array.from(shopMap.values());

  return <ConsultationWorkflow shops={shops} />;
}

BeautyConsultationsPage.authenticate = {
  permissions: adminOwnerAndStaffOnly,
};
BeautyConsultationsPage.Layout = Layout;

export const getStaticProps = async ({ locale }: any) => ({
  props: {
    ...(await serverSideTranslations(locale, ['common', 'form', 'table'])),
  },
});
