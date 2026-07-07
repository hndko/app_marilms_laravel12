<?php

namespace App\Contracts;

interface WaGatewayContract
{
    /**
     * Send a WhatsApp message to a phone number.
     *
     * @param string $phone Target phone number (e.g. 08123456789 or 628123456789)
     * @param string $message Text message content
     * @return bool True if successfully sent/queued
     */
    public function send(string $phone, string $message): bool;

    /**
     * Get the driver name.
     */
    public function getName(): string;
}
