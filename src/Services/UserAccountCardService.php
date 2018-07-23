<?php

namespace App\Services;

use App\Exception\CoreApplicationReadOnlyException;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidCredentialsException;
use App\Exception\UserAccountCardException;
use webignition\SimplyTestableUserModel\User;

class UserAccountCardService
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
     * @param string $stripe_card_token
     *
     * @throws CoreApplicationReadOnlyException
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     * @throws UserAccountCardException
     */
    public function associate(User $user, $stripe_card_token)
    {
        try {
            $this->coreApplicationHttpClient->post('user_card_associate', [
                'email' => $user->getUsername(),
                'stripe_card_token' => $stripe_card_token,
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
