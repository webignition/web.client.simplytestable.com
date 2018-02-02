<?php
namespace SimplyTestable\WebClientBundle\Services;

use Guzzle\Http\Exception\BadResponseException;
use Guzzle\Http\Exception\CurlException;
use SimplyTestable\WebClientBundle\Exception\UserAccountCardException;
use SimplyTestable\WebClientBundle\Model\User;

class UserPlanSubscriptionService extends CoreApplicationService
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
     * @var CoreApplicationRouter
     */
    private $coreApplicationRouter;

    /**
     * @param array $parameters
     * @param WebResourceService $webResourceService
     * @param StripeErrorFactory $stripeErrorFactory
     * @param CoreApplicationRouter $coreApplicationRouter
     */
    public function __construct(
        array $parameters,
        WebResourceService $webResourceService,
        StripeErrorFactory $stripeErrorFactory,
        CoreApplicationRouter $coreApplicationRouter
    ) {
        parent::__construct($parameters, $webResourceService);

        $this->stripeErrorFactory = $stripeErrorFactory;
        $this->coreApplicationRouter = $coreApplicationRouter;
    }

    /**
     * @param User $user
     * @param string $plan
     *
     * @return bool|int|null
     *
     * @throws UserAccountCardException
     */
    public function subscribe(User $user, $plan)
    {
        $requestUrl = $this->coreApplicationRouter->generate('user_plan_subscribe', [
            'email' => $user->getUsername(),
            'plan' => $plan
        ]);

        $request = $this->webResourceService->getHttpClientService()->postRequest($requestUrl);

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
