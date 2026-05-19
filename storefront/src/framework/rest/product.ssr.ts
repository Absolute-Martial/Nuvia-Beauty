import { getRevalidateDuration } from "./utils/revalidate";
import { API_ENDPOINTS } from "@framework/utils/endpoints";
import { GetStaticPathsContext, GetStaticProps } from "next";
import { serverSideTranslations } from "next-i18next/pages/serverSideTranslations";
import { QueryClient } from "react-query";
import { dehydrate } from "react-query/hydration";
import client from '@framework/utils/index'
import { SettingsQueryOptions } from "@type/index";

// This function gets called at build time
export async function getStaticPaths({ locales }: GetStaticPathsContext) {
  try {
    const products = await client.product.find({ limit: 100 });
    const productData = Array.isArray(products?.data)
      ? products.data.filter(({ slug }) => Boolean(slug))
      : [];
    const availableLocales = locales?.length ? locales : [undefined];
    const paths = productData.flatMap(({ slug }) =>
      availableLocales.map((locale) =>
        locale ? { params: { slug }, locale } : { params: { slug } }
      )
    );
    return {
      paths,
      fallback: "blocking",
    };
  } catch (error) {
    console.error('Failed to prebuild product pages', error);
    return {
      paths: [],
      fallback: "blocking",
    };
  }
}

export const getStaticProps: GetStaticProps = async ({ params, locale }) => {
  const slug = params?.slug as string;

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
      ({ queryKey }) => client.settings.findAll(queryKey[1] as SettingsQueryOptions)
    );
    const product = await client.product.findOne({slug, language: locale});
    return {
      props: {
        product,
        ...(await serverSideTranslations(locale!, [
          "common",
          "menu",
          "forms",
          "footer",
        ])),
        dehydratedState: JSON.parse(JSON.stringify(dehydrate(queryClient))),
      },
      revalidate: getRevalidateDuration(),
    };
  } catch (error) {
    console.error('Failed to build product page', { locale, params, error });
    return {
      notFound: true,
    };
  }
};
