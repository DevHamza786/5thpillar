<?php

namespace App\Services;

use App\Models\FinancialData;

class FinancialPlannerEngine
{
    public const PRODUCT_HAJJ = 'hajj';

    public const PRODUCT_UMRAH = 'umrah';

    /**
     * DB column holding projected fund value at the end of calendar year N (cumulative).
     */
    public static function maturityAttributeForYear(string $product, int $year): ?string
    {
        if ($product === self::PRODUCT_HAJJ) {
            return match ($year) {
                10 => 'year_ten',
                15 => 'year_fifteen',
                20 => 'year_twenty',
                25 => 'year_twenty_five',
                default => null,
            };
        }

        return match ($year) {
            5 => 'year_five',
            7 => 'year_seven',
            10 => 'year_ten',
            15 => 'year_fifteen',
            default => null,
        };
    }

    /**
     * @return int[]
     */
    public static function milestoneYears(string $product, int $selectedTerm): array
    {
        $all = $product === self::PRODUCT_HAJJ
            ? [10, 15, 20, 25]
            : [5, 7, 10, 15];

        return array_values(array_filter($all, fn (int $y) => $y <= $selectedTerm));
    }

    public static function termMaturityAttribute(string $product, int $term): string
    {
        $attr = self::maturityAttributeForYear($product, $term);
        if ($attr === null) {
            throw new \InvalidArgumentException("Unsupported term {$term} for product {$product}");
        }

        return $attr;
    }

    /**
     * @return array{totals: array{contribution: float, return_9: float, return_13: float}, charts: array<string, mixed>}
     */
    public static function buildResponseCharts(FinancialData $data9, FinancialData $data13, int $term, string $product): array
    {
        $termKey = self::termMaturityAttribute($product, $term);
        $totalContribution = (float) $data9->annual_contribution * $term;
        $return9 = (float) $data9->{$termKey};
        $return13 = (float) $data13->{$termKey};

        $milestones = self::milestoneYears($product, $term);
        $labels = [];
        $contributionDataset = [];
        $return9Dataset = [];
        $return13Dataset = [];

        foreach ($milestones as $y) {
            $key = self::maturityAttributeForYear($product, $y);
            if ($key === null) {
                continue;
            }
            $labels[] = "Year {$y}";
            $contributionDataset[] = (float) $data9->annual_contribution * $y;
            $return9Dataset[] = (float) $data9->{$key};
            $return13Dataset[] = (float) $data13->{$key};
        }

        return [
            'totals' => [
                'contribution' => $totalContribution,
                'return_9' => $return9,
                'return_13' => $return13,
            ],
            'charts' => [
                'line_chart' => [
                    'labels' => $labels,
                    'datasets' => [
                        [
                            'label' => 'Total Contribution',
                            'data' => $contributionDataset,
                            'borderColor' => '#8E8E8E',
                            'backgroundColor' => 'transparent',
                            'fill' => false,
                        ],
                        [
                            'label' => 'Estimated Return 9%',
                            'data' => $return9Dataset,
                            'borderColor' => '#B39246',
                            'backgroundColor' => 'transparent',
                            'fill' => false,
                        ],
                        [
                            'label' => 'Estimated Return 13%',
                            'data' => $return13Dataset,
                            'borderColor' => '#1a1a1a',
                            'backgroundColor' => 'transparent',
                            'fill' => false,
                        ],
                    ],
                ],
                'doughnut_9' => [
                    'labels' => ['Contribution', 'Gain'],
                    'datasets' => [[
                        'data' => [$totalContribution, max(0, $return9 - $totalContribution)],
                        'backgroundColor' => ['#1a1a1a', '#B39246'],
                    ]],
                ],
                'doughnut_13' => [
                    'labels' => ['Contribution', 'Gain'],
                    'datasets' => [[
                        'data' => [$totalContribution, max(0, $return13 - $totalContribution)],
                        'backgroundColor' => ['#1a1a1a', '#B39246'],
                    ]],
                ],
            ],
        ];
    }
}
