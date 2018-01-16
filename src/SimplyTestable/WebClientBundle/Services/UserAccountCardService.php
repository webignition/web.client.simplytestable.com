<?php
namespace SimplyTestable\WebClientBundle\Services;

use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\CurlException;
use SimplyTestable\WebClientBundle\Exception\UserAccountCardException;
use SimplyTestable\WebClientBundle\Model\User;

class UserAccountCardService extends CoreApplicationService
{
    const STRIPE_ERROR_HEADER_PREFIX = 'x-stripe-error-';
    const STRIPE_ERROR_KEY_MESSAGE = 'message';
    const STRIPE_ERROR_KEY_PARAM = 'param';
    const STRIPE_ERROR_KEY_CODE = 'code';

    /**
     * @var StripeErrorFactory
     */
    private $stripeErrorFactory;

    /**
     * @param array $parameters
     * @param WebResourceService $webResourceService
     * @param StripeErrorFactory $stripeErrorFactory
     */
    public function __construct(
        array $parameters,
        WebResourceService $webResourceService,
        StripeErrorFactory $stripeErrorFactory
    ) {
        parent::__construct($parameters, $webResourceService);

        $this->stripeErrorFactory = $stripeErrorFactory;
    }

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
            $stripeError = $this->stripeErrorFactory->createFromHttpResponse($response);

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
}
