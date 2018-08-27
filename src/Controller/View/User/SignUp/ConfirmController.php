<?php

namespace App\Controller\View\User\SignUp;

use App\Controller\View\User\AbstractUserController;
use App\Exception\InvalidAdminCredentialsException;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\FlashBagValues;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class ConfirmController extends AbstractUserController
{
    /**
     * @var UserService
     */
    private $userService;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        FlashBagValues $flashBagValues,
        UserService $userService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory, $flashBagValues);

        $this->userService = $userService;
    }

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
        $notificationKeys = [
            'token_resend_confirmation',
            'user_create_confirmation',
            'user_token_error',
            'token_resend_error',
            'user_error',
        ];

        $viewData = array_merge([
            'email' => $email,
            'token' => trim($request->query->get('token')),
        ], $this->flashBagValues->get([
            'token_resend_confirmation',
            'user_create_confirmation',
            'user_token_error',
            'token_resend_error',
        ]));

        if (!$this->userService->exists($email)) {
            $viewData['user_error'] = 'invalid-user';
        }

        if (isset($viewData['token_resend_error']) && $viewData['token_resend_error'] === 'invalid-user') {
            unset($viewData['token_resend_error']);
            $viewData['user_error'] = 'invalid-user';
        }

        $response = $this->cacheableResponseFactory->createResponse($request, $viewData);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        $viewData['has_notification'] = $this->hasNotification($notificationKeys, $viewData);

        return $this->renderWithDefaultViewParameters('user-sign-up-confirm.html.twig', $viewData, $response);
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
