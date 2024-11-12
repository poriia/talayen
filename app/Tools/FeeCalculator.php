<?php

namespace App\Tools;

class FeeCalculator
{
    private const FEE_PERCENTAGES = [
        ['min' => 0, 'max' => 1, 'percentage' => 0.02],
        ['min' => 1.0001, 'max' => 10, 'percentage' => 0.015],
        ['min' => 10.0001, 'max' => PHP_FLOAT_MAX, 'percentage' => 0.01],
    ];

    private const MIN_FEE = 50000;
    private const MAX_FEE = 5000000;

    public static function calculate(float $amount, float $price): float
    {
        $feePercent = self::getFeePercentage($amount);
        $fee = $amount * $price * $feePercent;

        if ($fee < self::MIN_FEE) {
            $fee = self::MIN_FEE;
        } elseif ($fee > self::MAX_FEE) {
            $fee = self::MAX_FEE;
        }

        return $fee;
    }

    private static function getFeePercentage(float $amount): float
    {
        foreach (self::FEE_PERCENTAGES as $tier) {
            if ($amount >= $tier['min'] && $amount <= $tier['max']) {
                return $tier['percentage'];
            }
        }

        return 0.01;
    }
}
