<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;
use App\Validator\TaxValidator;


class CalculatePriceRequest extends BaseApiRequest
{
    #[NotBlank]
    #[Positive]
    protected string $product;
    #[NotBlank]
    #[TaxValidator]
    protected ?string $taxNumber;

    protected ?string $couponCode;

    public function getCouponCode(): ?string
    {
        return $this->couponCode;
    }

    public function getTaxNumber(): ?string
    {
        return $this->taxNumber;
    }

    public function getProduct(): string
    {
        return $this->product;
    }

}
