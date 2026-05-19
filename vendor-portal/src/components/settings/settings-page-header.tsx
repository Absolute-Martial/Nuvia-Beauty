import { useTranslation } from 'next-i18next/pages';

export default function SettingsPageHeader({
  pageTitle,
}: {
  pageTitle: string;
}) {
  const { t } = useTranslation();
  return (
    <>
      <div className="flex pt-1 pb-5 sm:pb-8">
        <h1 className="text-lg font-semibold text-heading">{t(pageTitle)}</h1>
      </div>
    </>
  );
}
