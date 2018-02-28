<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use webignition\Model\Stripe\Event\Event as StripeEvent;
use webignition\SimplyTestableUserModel\User;

class UserStripeEventService
{
    /**
     * @var CoreApplicationHttpClient
     */
    private $coreApplicationHttpClient;

    /**
     * @var JsonResponseHandler
     */
    private $jsonResponseHandler;

    /**
     * @param CoreApplicationHttpClient $coreApplicationHttpClient
     * @param JsonResponseHandler $jsonResponseHandler
     */
    public function __construct(
        CoreApplicationHttpClient $coreApplicationHttpClient,
        JsonResponseHandler $jsonResponseHandler
    ) {
        $this->coreApplicationHttpClient = $coreApplicationHttpClient;
        $this->jsonResponseHandler = $jsonResponseHandler;
    }

    /**
     * @param User $user
     * @param string $type
     *
     * @return null|StripeEvent
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    public function getLatest(User $user, $type)
    {
        $list = $this->getList($user, $type);

        if (empty($list)) {
            return null;
        }

        return $list[0];
    }

    /**
     * @param User $user
     * @param string $type
     *
     * @return StripeEvent[]
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidContentTypeException
     * @throws InvalidCredentialsException
     */
    private function getList(User $user, $type)
    {
        $response = $this->coreApplicationHttpClient->get('user_list_stripe_events', [
            'email' => $user->getUsername(),
            'type' => $type,
        ]);

        $responseData = $this->jsonResponseHandler->handle($response);

        $list = [];

        foreach ($responseData as $eventData) {
            $list[] = new StripeEvent($eventData['stripe_event_data']);
        };

        return $list;
    }
}
