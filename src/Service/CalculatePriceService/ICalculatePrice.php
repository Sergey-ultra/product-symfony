<?php

namespace App\Service\CalculatePriceService;

use App\Entity\Product;

interface ICalculatePrice
{
    public function calculate(int $price, string $taxNumber, ?string $couponCode = null): int;
}
