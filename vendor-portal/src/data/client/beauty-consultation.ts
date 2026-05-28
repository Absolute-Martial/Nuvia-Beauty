import { API_ENDPOINTS } from './api-endpoints';
import { HttpClient } from './http-client';

export interface BeautyConsultationSession {
  id: string;
  internal_id: number;
  consultation_mode: 'guest' | 'new_customer' | 'returning_customer';
  session_state: string;
  notes?: string | null;
  saved_at?: string | null;
  discarded_at?: string | null;
  shop?: {
    id: number;
    name: string;
    slug: string;
  } | null;
  consultant?: {
    id: number;
    name: string;
    email: string;
  } | null;
  customer?: {
    id: number;
    name: string;
    email: string;
  } | null;
  profile?: {
    id: number;
    customer_name?: string | null;
    contact_email?: string | null;
    contact_phone?: string | null;
    skin_type_tags: string[];
    tone_tags: string[];
    undertone_tags: string[];
    concern_tags: string[];
    ingredient_tags: string[];
    avoid_tags: string[];
  } | null;
  snapshot?: {
    id: number;
    skin_type_tags: string[];
    tone_tags: string[];
    undertone_tags: string[];
    concern_tags: string[];
    ingredient_tags: string[];
    avoid_tags: string[];
    notes?: string | null;
  } | null;
  media_asset?: {
    id: number;
    disk_name: string;
    bucket: string;
    object_key: string;
    status: string;
    content_type: string;
  } | null;
  ai_tasks: Array<{
    id: number;
    provider: string;
    task_type: string;
    status: string;
    queued_at?: string | null;
    completed_at?: string | null;
  }>;
  analysis_results: Array<{
    id: number;
    provider: string;
    status: string;
    recommendation_count: number;
    completed_at?: string | null;
  }>;
}

export interface BeautyConsultationRecommendation {
  product_id: number;
  score: number;
  confidence: 'low' | 'medium' | 'high';
  reasons: string[];
  warnings: string[];
  breakdown: Record<string, number>;
  product: {
    id: number;
    name: string;
    slug: string;
    image?: any;
    shop_id?: number | null;
  };
}

export interface BeautyAnalysisStatusPayload {
  data: {
    session: BeautyConsultationSession;
    task: {
      id: number;
      provider: string;
      task_type: string;
      status: string;
      provider_task_id?: string | null;
      error_message?: string | null;
      queued_at?: string | null;
      started_at?: string | null;
      completed_at?: string | null;
    } | null;
    analysis_result: {
      id: number;
      provider: string;
      status: string;
      summary: {
        analysis_mode?: string;
        provider?: string;
        demo_mode?: boolean;
        message?: string;
        skin_type?: string;
        tone?: string;
        undertone?: string;
        key_concerns?: string[];
        observations?: string[];
      };
      normalized_traits: {
        skin_type_tags: string[];
        tone_tags: string[];
        undertone_tags: string[];
        concern_tags: string[];
        ingredient_tags: string[];
        avoid_tags: string[];
      };
      recommendation_count: number;
      completed_at?: string | null;
    } | null;
    recommendations: BeautyConsultationRecommendation[];
  };
}

export interface BeautyUploadSlotResponse {
  data: {
    media_id: number;
    storage_provider: string;
    disk_name: string;
    bucket: string;
    object_key: string;
    object_version?: string | null;
    upload_url: string;
    method: string;
    headers: Record<string, string>;
    expires_at: string;
  };
}

type SessionResponse = {
  data: {
    session: BeautyConsultationSession;
  };
};

type RecommendationResponse = {
  data: {
    recommendations: BeautyConsultationRecommendation[];
  };
};

export interface CreateBeautySessionPayload {
  shop_id: number;
  consultation_mode: 'guest' | 'new_customer' | 'returning_customer';
  customer_id?: number;
  user_profile_id?: number;
  customer_name?: string;
  contact_email?: string;
  contact_phone?: string;
  notes?: string;
  skin_type_tags?: string[];
  tone_tags?: string[];
  undertone_tags?: string[];
  concern_tags?: string[];
  ingredient_tags?: string[];
  avoid_tags?: string[];
}

export const beautyConsultationClient = {
  createSession(payload: CreateBeautySessionPayload) {
    return HttpClient.post<SessionResponse>(API_ENDPOINTS.BEAUTY_SESSIONS, payload);
  },
  getSession(sessionId: string) {
    return HttpClient.get<SessionResponse>(`${API_ENDPOINTS.BEAUTY_SESSIONS}/${sessionId}`);
  },
  attachMedia(sessionId: string, mediaAssetId: number) {
    return HttpClient.post<SessionResponse>(
      `${API_ENDPOINTS.BEAUTY_SESSIONS}/${sessionId}/attach-media`,
      { media_asset_id: mediaAssetId },
    );
  },
  startAnalysis(sessionId: string) {
    return HttpClient.post<BeautyAnalysisStatusPayload>(
      `${API_ENDPOINTS.BEAUTY_SESSIONS}/${sessionId}/analysis/start`,
      {},
    );
  },
  getAnalysisStatus(taskId: number) {
    return HttpClient.get<BeautyAnalysisStatusPayload>(
      `${API_ENDPOINTS.BEAUTY_ANALYSIS}/${taskId}/status`,
    );
  },
  saveSession(sessionId: string, payload?: { notes?: string; accepted_recommendation_ids?: number[] }) {
    return HttpClient.post<SessionResponse>(
      `${API_ENDPOINTS.BEAUTY_SESSIONS}/${sessionId}/save`,
      payload ?? {},
    );
  },
  discardSession(sessionId: string, payload?: { discard_reason?: string }) {
    return HttpClient.post<SessionResponse>(
      `${API_ENDPOINTS.BEAUTY_SESSIONS}/${sessionId}/discard`,
      payload ?? {},
    );
  },
  getRecommendations(sessionId: string, params?: { limit?: number }) {
    return HttpClient.get<RecommendationResponse>(
      `${API_ENDPOINTS.BEAUTY_SESSIONS}/${sessionId}/recommendations`,
      params ?? {},
    );
  },
  createUploadSlot(payload: {
    purpose: 'beauty_input';
    asset_type: string;
    owner_type: 'shop';
    owner_id: number;
    shop_id: number;
    session_id: string;
    file_name: string;
    content_type: string;
    size_bytes: number;
    checksum_sha256?: string;
  }) {
    return HttpClient.post<BeautyUploadSlotResponse>(
      API_ENDPOINTS.STORAGE_UPLOAD_SLOTS,
      payload,
    );
  },
  confirmMedia(mediaId: number) {
    return HttpClient.post<{ data: any }>(
      `${API_ENDPOINTS.STORAGE_MEDIA}/${mediaId}/confirm`,
      {},
    );
  },
};
