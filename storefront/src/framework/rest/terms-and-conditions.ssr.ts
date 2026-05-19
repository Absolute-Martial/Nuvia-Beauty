import { getRevalidateDuration } from "./utils/revalidate";
import { API_ENDPOINTS } from '@framework/utils/endpoints';
import client from '@framework/utils/index';
import { SettingsQueryOptions, TermsAndConditionsQueryOptions } from '@type/index';
import { GetStaticPathsContext, GetStaticProps } from 'next';
import { serverSideTranslations } from 'next-i18next/pages/serverSideTranslations';
import { QueryClient } from 'react-query';
import { dehydrate } from 'react-query/hydration';

// This function gets called at build time
export async function getStaticPaths({ locales }: GetStaticPathsContext) {
  try {
    const { data } = await client.shop.find({ is_active: '1' });
    const shopData = Array.isArray(data)
      ? data.filter((shop: any) => Boolean(shop?.slug))
      : [];
    const availableLocales = locales?.length ? locales : [undefined];
    const paths = shopData.flatMap((shop: any) =>
      availableLocales.map((locale) =>
        locale ? { params: { slug: shop.slug }, locale } : { params: { slug: shop.slug } }
      )
    );

    return { paths, fallback: 'blocking' };
  } catch (error) {
    console.error('Failed to prebuild terms pages', error);
    return { paths: [], fallback: 'blocking' };
  }
}

// This also gets called at build time
export const getStaticProps: GetStaticProps = async ({ params, locale }) => {
  const queryClient = new QueryClient({
    defaultOptions: {
      queries: {
        staleTime: Infinity,
      },
    },
  });

  try {
    await queryClient.prefetchQuery(
      [API_ENDPOINTS.SETTINGS, { language: locale }],
      ({ queryKey }) =>
        client.settings.findAll(queryKey[1] as SettingsQueryOptions)
    );
    const shop = await client.shop.findOne({
      slug: params!.slug as string,
      // language: locale,
    });
    await queryClient.prefetchInfiniteQuery(
      [
        API_ENDPOINTS.TERMS_AND_CONDITIONS,
        {
          shop_id: shop?.id,
          limit: 10,
          orderBy: 'created_at',
          sortedBy: 'DESC',
        },
      ],
      ({ queryKey }) => client.termsAndConditions.all(queryKey[1] as TermsAndConditionsQueryOptions)
    );
    return {
      props: {
        data: { shop },
        ...(await serverSideTranslations(locale!, [
          'common',
          'menu',
          'forms',
          'footer',
        ])),
        dehydratedState: JSON.parse(JSON.stringify(dehydrate(queryClient))),
      },
      revalidate: getRevalidateDuration(),
    };
  } catch (error) {
    console.error('Failed to build terms page', { locale, params, error });
    return {
      notFound: true,
    };
  }
};
