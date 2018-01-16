<?php
namespace SimplyTestable\WebClientBundle\Services;

use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Model\StripeError;

class StripeErrorFactory
{
    const STRIPE_ERROR_HEADER_PREFIX = 'x-stripe-error-';
    const STRIPE_ERROR_KEY_MESSAGE = 'message';
    const STRIPE_ERROR_KEY_PARAM = 'param';
    const STRIPE_ERROR_KEY_CODE = 'code';

    /**
     * @param Response $response
     *
     * @return StripeError
     */
    public function createFromHttpResponse(Response $response)
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
                $headerValues = $response->getHeader($headerKey)->toArray();

                if (count($headerValues)) {
                    $errorValue = $headerValues[0];
                }
            }

            $errorValues[] = $errorValue;
        }

        list($message, $param, $code) = $errorValues;

        return new StripeError($message, $param, $code);
    }
}
