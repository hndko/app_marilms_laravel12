<?php

namespace App\Services;

use App\Contracts\PaymentGatewayContract;
use App\Services\PaymentGateways\DokuGateway;
use App\Services\PaymentGateways\DuitkuGateway;
use App\Services\PaymentGateways\IpaymuGateway;
use App\Services\PaymentGateways\MidtransGateway;
use App\Services\PaymentGateways\XenditGateway;
use InvalidArgumentException;

class PaymentGatewayManager
{
    /**
     * Resolve a payment gateway driver instance by name.
     */
    public function resolve(string $gateway): PaymentGatewayContract
    {
        return match (strtolower(trim($gateway))) {
            'midtrans' => new MidtransGateway(),
            'xendit' => new XenditGateway(),
            'ipaymu' => new IpaymuGateway(),
            'doku' => new DokuGateway(),
            'duitku' => new DuitkuGateway(),
            default => throw new InvalidArgumentException("Payment gateway driver [{$gateway}] is not supported."),
        };
    }

    /**
     * Get list of supported gateway names.
     */
    public function getSupportedGateways(): array
    {
        return ['midtrans', 'xendit', 'ipaymu', 'doku', 'duitku'];
    }
}
