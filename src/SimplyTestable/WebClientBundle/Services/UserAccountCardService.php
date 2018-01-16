<?php
namespace SimplyTestable\WebClientBundle\Services;

use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\CurlException;
use Guzzle\Http\Message\Response;
use SimplyTestable\WebClientBundle\Exception\UserAccountCardException;
use SimplyTestable\WebClientBundle\Model\StripeError;
use SimplyTestable\WebClientBundle\Model\User;

class UserAccountCardService extends CoreApplicationService
{
    const STRIPE_ERROR_HEADER_PREFIX = 'x-stripe-error-';
    const STRIPE_ERROR_KEY_MESSAGE = 'message';
    const STRIPE_ERROR_KEY_PARAM = 'param';
    const STRIPE_ERROR_KEY_CODE = 'code';

    /**
     * @param User $user
     * @param string $stripe_card_token
     *
     * @return bool|int|null
     *
     * @throws UserAccountCardException
     */
    public function associate(User $user, $stripe_card_token)
    {
        $request = $this->webResourceService->getHttpClientService()->postRequest(
            $this->getUrl('user_card_associate', [
                'email' => $user->getUsername(),
                'stripe_card_token' => $stripe_card_token
            ])
        );

        $this->addAuthorisationToRequest($request);

        try {
            $response = $request->send();
        } catch (BadResponseException $badResponseException) {
            $response = $badResponseException->getResponse();
            $stripeError = $this->createStripeErrorFromHttpResponse($response);

            if (!$stripeError->isEmpty()) {
                throw new UserAccountCardException(
                    $stripeError->getMessage(),
                    $stripeError->getParam(),
                    $stripeError->getCode()
                );
            }
        } catch (CurlException $curlException) {
            return $curlException->getErrorNo();
        }

        return $response->getStatusCode() == 200 ? true : $response->getStatusCode();
    }

    /**
     * @param Response $response
     *
     * @return StripeError
     */
    private function createStripeErrorFromHttpResponse(Response $response)
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
