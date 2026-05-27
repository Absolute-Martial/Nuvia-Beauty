import client from '@framework/utils/index';
import {
  BeautyRecommendationInput,
  BeautyRecommendationResponse,
} from '@type/index';
import { useMutation } from 'react-query';

export function useBeautyRecommendations() {
  return useMutation<BeautyRecommendationResponse, Error, BeautyRecommendationInput>(
    (input) => client.beauty.generateRecommendations(input),
  );
}
