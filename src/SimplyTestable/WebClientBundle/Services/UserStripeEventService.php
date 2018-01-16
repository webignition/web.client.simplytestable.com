<?php
namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Exception\WebResourceException;
use webignition\Model\Stripe\Event\Event as StripeEvent;
use SimplyTestable\WebClientBundle\Model\User;
use webignition\WebResource\JsonDocument\JsonDocument;

class UserStripeEventService extends CoreApplicationService
{
    /**
     * @param User $user
     * @param string $type
     *
     * @return StripeEvent[]
     *
     * @throws WebResourceException
     */
    public function getList(User $user, $type)
    {
        $request = $this->webResourceService->getHttpClientService()->getRequest(
            $this->getUrl('user_list_stripe_events', [
                'email' => $user->getUsername(),
                'type' => $type
            ])
        );

        $this->addAuthorisationToRequest($request);

        /* @var JsonDocument $jsonDocument */
        $jsonDocument = $this->webResourceService->get($request);

        $responseObject = $jsonDocument->getContentObject();

        $list = [];

        foreach ($responseObject as $eventData) {
            $list[] = new StripeEvent($eventData->stripe_event_data);
        };

        return $list;
    }

    /**
     * @param User $user
     * @param string $type
     *
     * @return null|StripeEvent
     *
     * @throws WebResourceException
     */
    public function getLatest(User $user, $type)
    {
        $list = $this->getList($user, $type);

        if (empty($list)) {
            return null;
        }

        return $list[0];
    }
}
