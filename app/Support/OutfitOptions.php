<?php

namespace App\Support;

class OutfitOptions
{
    protected const OPTIONS = [
        'male' => [
            ['value' => 'casual_tee', 'label' => 'Casual Tee & Jeans', 'color' => '#2563EB'],
            ['value' => 'formal_suit', 'label' => 'Formal Suit', 'color' => '#1F2937'],
            ['value' => 'ethnic_kurta', 'label' => 'Ethnic Kurta', 'color' => '#B91C1C'],
            ['value' => 'sportswear', 'label' => 'Active Sportswear', 'color' => '#047857'],
            ['value' => 'business_casual', 'label' => 'Business Casual', 'color' => '#92400E'],
            ['value' => 'street_style', 'label' => 'Street Style', 'color' => '#6B21A8'],
        ],
        'female' => [
            ['value' => 'floral_frock', 'label' => 'Floral Frock', 'color' => '#DB2777'],
            ['value' => 'casual_denim', 'label' => 'Casual Denim', 'color' => '#1D4ED8'],
            ['value' => 'saree_traditional', 'label' => 'Traditional Saree', 'color' => '#9333EA'],
            ['value' => 'evening_gown', 'label' => 'Evening Gown', 'color' => '#7C3AED'],
            ['value' => 'business_formal', 'label' => 'Business Formal', 'color' => '#0E7490'],
            ['value' => 'ethnic_lehenga', 'label' => 'Ethnic Lehenga', 'color' => '#F59E0B'],
        ],
        'child' => [
            ['value' => 'playful_casual', 'label' => 'Playful Casual', 'color' => '#10B981'],
            ['value' => 'school_uniform', 'label' => 'School Uniform', 'color' => '#2563EB'],
            ['value' => 'party_dress', 'label' => 'Party Dress', 'color' => '#EF4444'],
            ['value' => 'sports_kit', 'label' => 'Sports Kit', 'color' => '#F97316'],
            ['value' => 'winter_wear', 'label' => 'Winter Wear', 'color' => '#6366F1'],
            ['value' => 'traditional_festive', 'label' => 'Traditional Festive', 'color' => '#FBBF24'],
        ],
    ];

    public static function all(): array
    {
        return self::OPTIONS;
    }

    public static function emptySelection(): array
    {
        return [
            'male' => [],
            'female' => [],
            'child' => [],
        ];
    }

    public static function normalize($selection): array
    {
        $normalized = self::emptySelection();

        if (! is_array($selection)) {
            return $normalized;
        }

        $labelIndex = self::labelIndex();

        foreach ($normalized as $category => $_) {
            if (empty($selection[$category]) || ! is_array($selection[$category])) {
                continue;
            }

            $normalized[$category] = array_values(array_unique(array_filter(
                $selection[$category],
                static function ($value) use ($labelIndex, $category) {
                    return is_string($value) && isset($labelIndex[$category][$value]);
                }
            )));
        }

        return $normalized;
    }

    public static function summarize($selection): string
    {
        if (is_string($selection)) {
            return $selection;
        }

        if (! is_array($selection)) {
            return '';
        }

        $labelIndex = self::labelIndex();
        $segments = [];

        foreach (self::emptySelection() as $category => $_) {
            $values = $selection[$category] ?? [];

            if (! $values) {
                continue;
            }

            $labels = array_map(
                static function ($value) use ($labelIndex, $category) {
                    return $labelIndex[$category][$value] ?? self::humanize($value);
                },
                $values
            );

            $segments[] = ucfirst($category) . ': ' . implode(', ', $labels);
        }

        return implode(' | ', $segments);
    }

    public static function labelIndex(): array
    {
        static $index = null;

        if ($index !== null) {
            return $index;
        }

        $index = [];

        foreach (self::OPTIONS as $category => $options) {
            $index[$category] = [];

            foreach ($options as $option) {
                $index[$category][$option['value']] = $option['label'];
            }
        }

        return $index;
    }

    public static function colorIndex(): array
    {
        static $index = null;

        if ($index !== null) {
            return $index;
        }

        $index = [];

        foreach (self::OPTIONS as $category => $options) {
            $index[$category] = [];

            foreach ($options as $option) {
                $index[$category][$option['value']] = $option['color'];
            }
        }

        return $index;
    }

    protected static function humanize(string $value): string
    {
        return ucwords(str_replace('_', ' ', $value));
    }

    public static function validateAndNormalize($outfitData)
    {
        if (is_string($outfitData)) {
            $decoded = json_decode($outfitData, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                $outfitData = $decoded;
            } else {
                return null;
            }
        }

        if (!is_array($outfitData)) {
            return null;
        }

        $normalized = self::normalize($outfitData);

        // Check if any outfits are selected
        $hasOutfits = false;
        foreach (['male', 'female', 'child'] as $category) {
            if (!empty($normalized[$category])) {
                $hasOutfits = true;
                break;
            }
        }

        return $hasOutfits ? $normalized : null;
    }
}

