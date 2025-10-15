<?php

namespace App\Modules\Sms\Contracts;

interface SmsProviderInterface
{
    /**
     * Send plain text SMS to a single recipient.
     *
     * @param string $to E.164 or local phone number
     * @param string $message Message body
     * @param array $options Provider-specific options
     * @return array Standardized result payload
     */
    public function send(string $to, string $message, array $options = []): array;

    /**
     * Send a templated SMS to a single recipient.
     *
     * @param string $to
     * @param string $template Logical template name
     * @param array $variables Key/value pairs for template variables
     * @param array $options Provider-specific options
     * @return array Standardized result payload
     */
    public function sendTemplate(string $to, string $template, array $variables = [], array $options = []): array;

    /**
     * Send plain text SMS to multiple recipients.
     * @param array $recipients
     * @param string $message
     * @param array $options
     * @return array Standardized result payload
     */
    public function sendBulk(array $recipients, string $message, array $options = []): array;
}