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
};
