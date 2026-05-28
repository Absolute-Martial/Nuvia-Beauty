<?php

namespace App\Domains\Beauty\Services;

use App\Domains\Beauty\Models\BeautySession;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class NormalizePerfectCorpResultService
{
    public function __construct(
        protected FeatureExtractorService $features,
    ) {
    }

    public function demoResult(BeautySession $session): array
    {
        $baseTraits = $this->features->normalizeInput([
            'skin_type_tags' => $session->currentSnapshot?->skin_type_tags ?? [],
            'tone_tags' => $session->currentSnapshot?->tone_tags ?? [],
            'undertone_tags' => $session->currentSnapshot?->undertone_tags ?? [],
            'concern_tags' => $session->currentSnapshot?->concern_tags ?? [],
            'ingredient_tags' => $session->currentSnapshot?->ingredient_tags ?? [],
            'avoid_tags' => $session->currentSnapshot?->avoid_tags ?? [],
        ]);

        $hashBucket = hexdec(substr(md5($session->public_id), 0, 2)) % 3;
        $fallbackConcerns = [
            0 => ['dark_spot'],
            1 => ['texture'],
            2 => ['oil_control'],
        ];

        $normalizedTraits = [
            'skin_type_tags' => $baseTraits['skin_type_tags'] ?: ['combination'],
            'tone_tags' => $baseTraits['tone_tags'] ?: ['medium'],
            'undertone_tags' => $baseTraits['undertone_tags'] ?: ['neutral'],
            'concern_tags' => $baseTraits['concern_tags'] ?: $fallbackConcerns[$hashBucket],
            'ingredient_tags' => $baseTraits['ingredient_tags'],
            'avoid_tags' => $baseTraits['avoid_tags'],
        ];

        return [
            'summary_payload' => [
                'analysis_mode' => 'demo',
                'provider' => 'perfect_corp_demo',
                'demo_mode' => true,
                'message' => 'Demo analysis generated from the seller consultation profile and attached private media.',
                'skin_type' => $this->headline($normalizedTraits['skin_type_tags'][0] ?? 'combination'),
                'tone' => $this->headline($normalizedTraits['tone_tags'][0] ?? 'medium'),
                'undertone' => $this->headline($normalizedTraits['undertone_tags'][0] ?? 'neutral'),
                'key_concerns' => array_map([$this, 'headline'], $normalizedTraits['concern_tags']),
                'observations' => [
                    'Demo mode uses consultation inputs to emulate a provider-backed analysis lifecycle.',
                    'Normalized traits are safe for frontend display and recommendation generation.',
                ],
            ],
            'normalized_traits' => $normalizedTraits,
        ];
    }

    public function liveResult(array $providerPayload, BeautySession $session): array
    {
        $rawTraits = [
            'skin_type_tags' => $this->extractTagList($providerPayload, [
                'analysis.skin_type.tags',
                'skin_type.tags',
            ]),
            'tone_tags' => $this->extractTagList($providerPayload, [
                'analysis.tone.tags',
                'tone.tags',
            ]),
            'undertone_tags' => $this->extractTagList($providerPayload, [
                'analysis.undertone.tags',
                'undertone.tags',
            ]),
            'concern_tags' => $this->extractTagList($providerPayload, [
                'analysis.concerns.tags',
                'concerns.tags',
            ]),
            'ingredient_tags' => $session->currentSnapshot?->ingredient_tags ?? [],
            'avoid_tags' => $session->currentSnapshot?->avoid_tags ?? [],
        ];

        $normalizedTraits = $this->features->normalizeInput($rawTraits);

        return [
            'summary_payload' => [
                'analysis_mode' => 'live',
                'provider' => 'perfect_corp',
                'demo_mode' => false,
                'message' => (string) Arr::get($providerPayload, 'message', 'Perfect Corp analysis completed.'),
                'skin_type' => $this->headline($normalizedTraits['skin_type_tags'][0] ?? 'unknown'),
                'tone' => $this->headline($normalizedTraits['tone_tags'][0] ?? 'unknown'),
                'undertone' => $this->headline($normalizedTraits['undertone_tags'][0] ?? 'unknown'),
                'key_concerns' => array_map([$this, 'headline'], $normalizedTraits['concern_tags']),
                'observations' => Arr::wrap(Arr::get($providerPayload, 'analysis.observations', [])),
            ],
            'normalized_traits' => $normalizedTraits,
        ];
    }

    protected function extractTagList(array $payload, array $paths): array
    {
        foreach ($paths as $path) {
            $value = Arr::get($payload, $path);

            if ($value !== null) {
                return Arr::wrap($value);
            }
        }

        return [];
    }

    protected function headline(string $value): string
    {
        return Str::headline(str_replace('_', ' ', $value));
    }
}
