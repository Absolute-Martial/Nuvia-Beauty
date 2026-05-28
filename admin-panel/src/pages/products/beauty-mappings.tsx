import Link from 'next/link';
import { useState } from 'react';
import { serverSideTranslations } from 'next-i18next/pages/serverSideTranslations';
import { useTranslation } from 'next-i18next/pages';
import Card from '@/components/common/card';
import PageHeading from '@/components/common/page-heading';
import Layout from '@/components/layouts/admin';
import Input from '@/components/ui/input';
import Button from '@/components/ui/button';
import Loader from '@/components/ui/loader/loader';
import ErrorMessage from '@/components/ui/error-message';
import BeautyMappingStatusBadge from '@/components/product/beauty-mapping-status-badge';
import {
  useBeautyProductMappingOverviewQuery,
  useRecomputeBeautySignalsMutation,
} from '@/data/beauty-product-mapping';
import { Routes } from '@/config/routes';
import { adminOnly } from '@/utils/auth-utils';

function SummaryCard({
  label,
  value,
}: {
  label: string;
  value: number;
}) {
  return (
    <div className="rounded border border-border-base bg-gray-50 p-4">
      <p className="text-sm text-body">{label}</p>
      <p className="mt-2 text-2xl font-semibold text-heading">{value}</p>
    </div>
  );
}

export default function BeautyMappingOverviewPage() {
  const { t } = useTranslation();
  const [searchInput, setSearchInput] = useState('');
  const [searchTerm, setSearchTerm] = useState('');
  const [page, setPage] = useState(1);
  const { products, summary, paginatorInfo, error, isLoading, isFetching } =
    useBeautyProductMappingOverviewQuery({
      name: searchTerm || undefined,
      page,
      limit: 20,
    });
  const { mutate: recomputeSignals, isLoading: recomputing } =
    useRecomputeBeautySignalsMutation();

  const handleSearch = () => {
    setPage(1);
    setSearchTerm(searchInput.trim());
  };

  if (isLoading && !summary) {
    return <Loader text={t('common:text-loading')} />;
  }

  if (error) {
    return <ErrorMessage message={(error as Error).message} />;
  }

  return (
    <div className="space-y-8">
      <Card className="flex flex-col gap-5">
        <div className="flex flex-col gap-5 md:flex-row md:items-end md:justify-between">
          <div>
            <PageHeading title="Beauty Mapping Overview" />
            <p className="mt-3 text-sm text-body">
              Review mapped, unmapped, and recommendation-ready products. Recompute product signals only for mapped products.
            </p>
          </div>
          <div className="flex flex-col gap-3 sm:flex-row">
            <Input
              name="beauty-mapping-search"
              label="Search products"
              value={searchInput}
              onChange={(event) => setSearchInput(event.target.value)}
              placeholder="Search by product name"
            />
            <div className="flex gap-3">
              <Button type="button" variant="outline" onClick={handleSearch}>
                Search
              </Button>
              <Button
                type="button"
                loading={recomputing}
                disabled={recomputing || !summary?.mapped_products}
                onClick={() => recomputeSignals({ productIds: [] })}
              >
                Recompute all mapped
              </Button>
            </div>
          </div>
        </div>
        {isFetching ? (
          <p className="text-sm text-body">Refreshing overview data...</p>
        ) : null}
      </Card>

      <div className="grid grid-cols-1 gap-4 md:grid-cols-5">
        <SummaryCard label="Total products" value={summary?.total_products ?? 0} />
        <SummaryCard label="Mapped" value={summary?.mapped_products ?? 0} />
        <SummaryCard label="Unmapped" value={summary?.unmapped_products ?? 0} />
        <SummaryCard label="Partial" value={summary?.partial_products ?? 0} />
        <SummaryCard
          label="Ready for recommendation"
          value={summary?.ready_products ?? 0}
        />
      </div>

      <Card>
        <div className="overflow-x-auto">
          <table className="min-w-full divide-y divide-border-base text-sm">
            <thead>
              <tr className="text-left text-body">
                <th className="px-4 py-3 font-semibold">Product</th>
                <th className="px-4 py-3 font-semibold">Shop</th>
                <th className="px-4 py-3 font-semibold">Status</th>
                <th className="px-4 py-3 font-semibold">Mapping coverage</th>
                <th className="px-4 py-3 font-semibold">Signal score</th>
                <th className="px-4 py-3 font-semibold">Actions</th>
              </tr>
            </thead>
            <tbody className="divide-y divide-border-base">
              {products.length ? (
                products.map((product) => (
                  <tr key={product.id} className="align-top">
                    <td className="px-4 py-4">
                      <p className="font-medium text-heading">{product.name}</p>
                      <p className="mt-1 text-xs text-body">
                        #{product.id} {product.type_name ? `· ${product.type_name}` : ''}
                      </p>
                    </td>
                    <td className="px-4 py-4 text-body">
                      {product.shop_name ?? 'Unassigned'}
                    </td>
                    <td className="px-4 py-4">
                      <BeautyMappingStatusBadge status={product.mapping_status} />
                    </td>
                    <td className="px-4 py-4 text-body">
                      <p>{product.mapping_dimension_count}/5 recommendation dimensions</p>
                      <p className="mt-1 text-xs">
                        Avoid tags: {product.has_avoid_tags ? 'yes' : 'no'} · Explanation template:{' '}
                        {product.has_explanation_template ? 'yes' : 'no'}
                      </p>
                    </td>
                    <td className="px-4 py-4 text-body">
                      {product.signal ? (
                        <>
                          <p>Score: {Number(product.signal.weighted_score ?? 0).toFixed(2)}</p>
                          <p className="mt-1 text-xs">
                            Views {product.signal.views} · Cart {product.signal.add_to_cart} · Purchases{' '}
                            {product.signal.purchases}
                          </p>
                        </>
                      ) : (
                        'Not recomputed yet'
                      )}
                    </td>
                    <td className="px-4 py-4">
                      <div className="flex flex-col gap-2">
                        <Link
                          href={Routes.product.editWithoutLang(product.slug)}
                          className="text-sm font-semibold text-accent"
                        >
                          Edit product mapping
                        </Link>
                        <Button
                          type="button"
                          variant="outline"
                          size="small"
                          loading={recomputing}
                          disabled={
                            recomputing || product.mapping_status === 'missing_mapping'
                          }
                          onClick={() => recomputeSignals({ productIds: [product.id] })}
                        >
                          Recompute product
                        </Button>
                      </div>
                    </td>
                  </tr>
                ))
              ) : (
                <tr>
                  <td
                    className="px-4 py-8 text-center text-body"
                    colSpan={6}
                  >
                    No products matched the current beauty mapping filters.
                  </td>
                </tr>
              )}
            </tbody>
          </table>
        </div>

        <div className="mt-6 flex items-center justify-between gap-3">
          <p className="text-sm text-body">
            Page {paginatorInfo?.current_page ?? 1} of {paginatorInfo?.last_page ?? 1}
          </p>
          <div className="flex gap-3">
            <Button
              type="button"
              variant="outline"
              disabled={!paginatorInfo || paginatorInfo.current_page <= 1}
              onClick={() => setPage((current) => Math.max(current - 1, 1))}
            >
              Previous
            </Button>
            <Button
              type="button"
              variant="outline"
              disabled={
                !paginatorInfo ||
                paginatorInfo.current_page >= paginatorInfo.last_page
              }
              onClick={() =>
                setPage((current) =>
                  paginatorInfo ? Math.min(current + 1, paginatorInfo.last_page) : current,
                )
              }
            >
              Next
            </Button>
          </div>
        </div>
      </Card>
    </div>
  );
}

BeautyMappingOverviewPage.authenticate = {
  permissions: adminOnly,
};
BeautyMappingOverviewPage.Layout = Layout;

export const getStaticProps = async ({ locale }: any) => ({
  props: {
    ...(await serverSideTranslations(locale, ['common', 'form', 'table'])),
  },
});
