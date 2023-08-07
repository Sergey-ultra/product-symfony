<?php

namespace App\Service\PaymentService;

interface IPaymentService
{
    public function process(int $price): void;
}
