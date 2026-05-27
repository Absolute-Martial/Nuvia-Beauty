<?php

namespace App\Domains\Beauty\Services;

class RecommendationExplanationService
{
    public function reasons(array $input, array $mapping, array $productTags, array $breakdown): array
    {
        $reasons = [];

        if ($breakdown['skin_type_match'] > 0 && $match = $this->firstMatch($input['skin_type_tags'], $mapping['skin_type_tags'])) {
            $reasons[] = 'Matches ' . $match . ' skin profile';
        }

        if ($breakdown['concern_match'] > 0 && $match = $this->firstMatch($input['concern_tags'], $mapping['concern_tags'])) {
            $reasons[] = 'Targets ' . str_replace('-', ' ', $match) . ' concern';
        }

        if ($breakdown['tone_match'] > 0 && $match = $this->firstMatch($input['tone_tags'], $mapping['tone_tags'])) {
            $reasons[] = 'Aligned with ' . str_replace('-', ' ', $match) . ' tone preference';
        }

        if ($breakdown['undertone_match'] > 0 && $match = $this->firstMatch($input['undertone_tags'], $mapping['undertone_tags'])) {
            $reasons[] = 'Supports ' . str_replace('-', ' ', $match) . ' undertone';
        }

        if ($breakdown['ingredient_match'] > 0 && $match = $this->firstMatch($input['ingredient_tags'], $mapping['ingredient_tags'])) {
            $reasons[] = 'Includes preferred ingredient focus: ' . str_replace('-', ' ', $match);
        }

        if ($breakdown['product_tag_signal'] > 0 && !empty($productTags)) {
            $reasons[] = 'Product tagging supports this recommendation';
        }

        return array_values(array_unique($reasons));
    }

    public function warnings(array $input, array $mapping): array
    {
        $overlap = array_values(array_intersect($input['avoid_tags'], $mapping['avoid_tags']));

        return array_map(
            fn ($tag) => 'Avoid if sensitive to ' . str_replace('-', ' ', $tag),
            $overlap
        );
    }

    protected function firstMatch(array $left, array $right): ?string
    {
        $match = array_values(array_intersect($left, $right));

        return $match[0] ?? null;
    }
}
