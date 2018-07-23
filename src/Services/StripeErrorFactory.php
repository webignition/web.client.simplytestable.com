<?php

namespace App\Services;

use Psr\Http\Message\ResponseInterface;
use App\Model\StripeError;

class StripeErrorFactory
{
    const STRIPE_ERROR_HEADER_PREFIX = 'x-stripe-error-';
    const STRIPE_ERROR_KEY_MESSAGE = 'message';
    const STRIPE_ERROR_KEY_PARAM = 'param';
    const STRIPE_ERROR_KEY_CODE = 'code';

    /**
     * @param ResponseInterface $response
     *
     * @return StripeError
     */
    public function createFromHttpResponse(ResponseInterface $response)
    {
        $stripeErrorHeaderKeys = [
            self::STRIPE_ERROR_HEADER_PREFIX . self::STRIPE_ERROR_KEY_MESSAGE,
            self::STRIPE_ERROR_HEADER_PREFIX . self::STRIPE_ERROR_KEY_PARAM,
            self::STRIPE_ERROR_HEADER_PREFIX . self::STRIPE_ERROR_KEY_CODE,
        ];

        $errorValues = [];

        foreach ($stripeErrorHeaderKeys as $headerKey) {
            $errorValue = '';

            if ($response->hasHeader($headerKey)) {
                $headerValue = $response->getHeaderLine($headerKey);

                if (!empty($headerValue)) {
                    $errorValue = $headerValue;
                }
            }

            $errorValues[] = $errorValue;
        }

        list($message, $param, $code) = $errorValues;

        return new StripeError($message, $param, $code);
    }
}
