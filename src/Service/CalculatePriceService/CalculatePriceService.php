<?php

namespace App\Service\CalculatePriceService;

class CalculatePriceService implements ICalculatePrice
{
    private int $price;
    public const PATTERN_MAP_TAX = [
        '#DE\d{9}#' => 19,
        '#IT\d{11}#' => 22,
        '#GR\d{9}#' => 20,
        '#FR\s{2}\d{9}#' => 24,
    ];

    public function calculate(int $price, string $taxNumber, ?string $couponCode = null): int
    {
        $this->price = $price;
        if ($couponCode) {
            $this->processCoupon($couponCode);
        }

        $this->processTax($taxNumber);

        return $this->price;
    }

    private function processCoupon(string $couponCode): void
    {
        if (preg_match('#(D)(\d{1,2})#', $couponCode, $matches)) {
            $this->price -= (int)$matches[2];
        } else if (preg_match('#(P)(\d{1,2})#', $couponCode, $matches)) {
            $this->price = ($this->price * (100 - (int)$matches[2])) / 100;
        }
    }

    private function processTax(string $taxNumber): void
    {
        $taxRate = 0;
        foreach (self::PATTERN_MAP_TAX as $pattern => $currentTaxRate) {
            if (preg_match($pattern, $taxNumber, $matches)) {
                $taxRate = $currentTaxRate;
                break;
            }
        }

        $this->price = ($this->price * (100 + $taxRate)) / 100;
    }
}
