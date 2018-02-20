<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\SignUp;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\FlashBagValues;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ConfirmController extends BaseViewController implements IEFiltered
{
    /**
     * @param Request $request
     * @param string $email
     *
     * @return Response
     *
     *
     * @throws InvalidAdminCredentialsException
     */
    public function indexAction(Request $request, $email)
    {
        $cacheValidatorService = $this->container->get(CacheValidatorService::class);
        $userService = $this->container->get(UserService::class);
        $flashBagValuesService = $this->container->get(FlashBagValues::class);
        $templating = $this->container->get('templating');

        $notificationKeys = [
            'token_resend_confirmation',
            'user_create_confirmation',
            'user_token_error',
            'token_resend_error',
        ];

        $viewData = array_merge([
            'email' => $email,
            'token' => trim($request->query->get('token')),
        ], $flashBagValuesService->get([
            'token_resend_confirmation',
            'user_create_confirmation',
            'user_token_error',
            'token_resend_error',
        ]));

        if (!$userService->exists($email)) {
            $viewData['user_error'] = 'invalid-user';
        }

        if (isset($viewData['token_resend_error']) && $viewData['token_resend_error'] === 'invalid-user') {
            unset($viewData['token_resend_error']);
            $viewData['user_error'] = 'invalid-user';
        }

        $response = $cacheValidatorService->createResponse($request, $viewData);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        $viewData['has_notification'] = $this->hasNotification($notificationKeys, $viewData);

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/User/SignUp/Confirm:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);

        return $response;
    }

    /**
     * @param array $notificationKeys
     * @param array $viewData
     *
     * @return bool
     */
    private function hasNotification(array $notificationKeys, array $viewData)
    {
        foreach ($notificationKeys as $notificationKey) {
            if (array_key_exists($notificationKey, $viewData)) {
                return true;
            }
        }

        return false;
    }
}
