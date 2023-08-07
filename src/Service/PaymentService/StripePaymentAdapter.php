<?php

namespace App\Service\PaymentService;

class StripePaymentAdapter implements IPaymentService
{
    private readonly StripePaymentProcessor $paymentService;

    public function __construct()
    {
        $this->paymentService = new StripePaymentProcessor();
    }

    public function process(int $price): void
    {
        $this->paymentService->processPayment($price);
    }
}
