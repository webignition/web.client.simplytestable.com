<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationReadOnlyException;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Exception\UserAccountCardException;
use webignition\SimplyTestableUserModel\User;

class UserPlanSubscriptionService
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
     * @var CoreApplicationHttpClient
     */
    private $coreApplicationHttpClient;

    /**
     * @param StripeErrorFactory $stripeErrorFactory
     * @param CoreApplicationHttpClient $coreApplicationHttpClient
     */
    public function __construct(
        StripeErrorFactory $stripeErrorFactory,
        CoreApplicationHttpClient $coreApplicationHttpClient
    ) {
        $this->stripeErrorFactory = $stripeErrorFactory;
        $this->coreApplicationHttpClient = $coreApplicationHttpClient;
    }

    /**
     * @param User $user
     * @param string $plan
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     * @throws UserAccountCardException
     */
    public function subscribe(User $user, $plan)
    {
        try {
            $this->coreApplicationHttpClient->post('user_plan_subscribe', [
                'email' => $user->getUsername(),
                'plan' => $plan
            ]);
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            if (400 === $coreApplicationRequestException->getCode()) {
                $response = $coreApplicationRequestException->getResponse();
                $stripeError = $this->stripeErrorFactory->createFromHttpResponse($response);

                if (!$stripeError->isEmpty()) {
                    throw new UserAccountCardException(
                        $stripeError->getMessage(),
                        $stripeError->getParam(),
                        $stripeError->getCode()
                    );
                }
            }

            throw $coreApplicationRequestException;
        }
    }
}
