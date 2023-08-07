<?php

namespace App\Service\PaymentService;

class PaypalPaymentAdapter implements IPaymentService
{
    private readonly PaypalPaymentProcessor $paymentService;
    public function __construct()
    {
        $this->paymentService = new PaypalPaymentProcessor();
    }

    public function process(int $price): void
    {
        $this->paymentService->pay($price);
    }
}
