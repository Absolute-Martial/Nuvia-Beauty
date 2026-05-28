import { API_ENDPOINTS } from './api-endpoints';
import { HttpClient } from './http-client';

export interface BeautyProductMapping {
  id: number;
  product_id: number;
  concern_tags: string[];
  skin_type_tags: string[];
  tone_tags: string[];
  undertone_tags: string[];
  ingredient_tags: string[];
  avoid_tags: string[];
  explanation_template?: string | null;
}

export type BeautyMappingStatus =
  | 'missing_mapping'
  | 'partial_mapping'
  | 'ready_for_recommendation';

export interface BeautyProductMappingPayload {
  product_id: number;
  concern_tags: string[];
  skin_type_tags: string[];
  tone_tags: string[];
  undertone_tags: string[];
  ingredient_tags: string[];
  avoid_tags: string[];
  explanation_template?: string;
}

interface BeautyProductMappingCollectionResponse {
  data: {
    data: BeautyProductMapping[];
  };
}

interface BeautyProductMappingRecordResponse {
  data: BeautyProductMapping;
}

interface ProductSignalRecomputeResponse {
  data: {
    product_signal_recompute: {
      product_ids: number[];
      recomputed_count: number;
      signal_version: string;
    };
  };
}

export interface BeautyMappingOverviewProduct {
  id: number;
  name: string;
  slug: string;
  shop_id?: number | null;
  shop_name?: string | null;
  type_name?: string | null;
  mapping_id?: number | null;
  mapping_status: BeautyMappingStatus;
  mapping_dimension_count: number;
  has_avoid_tags: boolean;
  has_explanation_template: boolean;
  signal?: {
    weighted_score?: number | null;
    views: number;
    add_to_cart: number;
    purchases: number;
    last_recomputed_at?: string | null;
  } | null;
}

export interface BeautyMappingOverviewSummary {
  total_products: number;
  mapped_products: number;
  unmapped_products: number;
  partial_products: number;
  ready_products: number;
}

interface BeautyMappingOverviewResponse {
  data: {
    summary: BeautyMappingOverviewSummary;
    products: {
      data: BeautyMappingOverviewProduct[];
      current_page: number;
      last_page: number;
      per_page: number;
      total: number;
    };
  };
}

export const beautyProductMappingClient = {
  listByProduct(productId: number, shopId?: number | string) {
    return HttpClient.get<BeautyProductMappingCollectionResponse>(
      API_ENDPOINTS.BEAUTY_PRODUCT_MAPPINGS,
      {
        product_id: productId,
        limit: 1,
        ...(shopId ? { shop_id: shopId } : {}),
      },
    );
  },
  create(data: BeautyProductMappingPayload) {
    return HttpClient.post<BeautyProductMappingRecordResponse>(
      API_ENDPOINTS.BEAUTY_PRODUCT_MAPPINGS,
      data,
    );
  },
  update(id: number, data: BeautyProductMappingPayload) {
    return HttpClient.put<BeautyProductMappingRecordResponse>(
      `${API_ENDPOINTS.BEAUTY_PRODUCT_MAPPINGS}/${id}`,
      data,
    );
  },
  overview(params?: {
    name?: string;
    shop_id?: number | string;
    page?: number;
    limit?: number;
  }) {
    return HttpClient.get<BeautyMappingOverviewResponse>(
      API_ENDPOINTS.ADMIN_BEAUTY_PRODUCT_MAPPINGS_OVERVIEW,
      params ?? {},
    );
  },
  recompute(productIds?: number[]) {
    return HttpClient.post<ProductSignalRecomputeResponse>(
      API_ENDPOINTS.ADMIN_BEAUTY_RECOMMENDATIONS_RECOMPUTE,
      {
        product_ids: productIds ?? [],
      },
    );
  },
};
