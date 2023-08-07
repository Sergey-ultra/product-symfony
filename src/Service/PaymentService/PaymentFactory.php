<?php

namespace App\Service\PaymentService;

use InvalidArgumentException;

final class PaymentFactory
{
    public static function factory(string $type): IPaymentService
    {
        return match ($type) {
            'paypal' => new PaypalPaymentAdapter(),
            'stripe' => new StripePaymentAdapter(),
            default => throw new InvalidArgumentException('Unknown payment service given'),
        };
    }
}
