<?php

namespace App\Controller\View\User\SignUp;

use App\Controller\View\User\AbstractUserController;
use App\Exception\InvalidAdminCredentialsException;
use App\Services\CacheValidatorService;
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

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param FlashBagValues $flashBagValues
     * @param UserService $userService
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        FlashBagValues $flashBagValues,
        UserService $userService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator, $flashBagValues);

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

        $response = $this->cacheValidator->createResponse($request, $viewData);

        if ($this->cacheValidator->isNotModified($response)) {
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
