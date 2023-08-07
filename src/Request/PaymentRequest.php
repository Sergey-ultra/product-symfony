<?php

namespace App\Request;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Positive;

class PaymentRequest extends BaseApiRequest
{
    #[NotBlank([])]
    #[Positive([])]
    protected string $product;

    protected ?string $taxNumber;

    protected ?string $couponCode;

    protected string $paymentProcessor;

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

    public function getPaymentProcessor(): string
    {
        return $this->paymentProcessor;
    }
}
