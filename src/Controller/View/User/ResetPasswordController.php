<?php

namespace App\Controller\View\User;

use App\Controller\Action\User\ResetPasswordController as ResetPasswordActionController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\FlashBagValues;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class ResetPasswordController extends AbstractUserController
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
        UserService $userService,
        FlashBagValues $flashBagValues
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory, $flashBagValues);

        $this->userService = $userService;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function requestAction(Request $request)
    {
        $viewData = array_merge([
            'email' => trim($request->query->get('email')),
        ], $this->flashBagValues->get([
            'user_reset_password_error',
            'user_reset_password_confirmation',
        ]));

        $response = $this->cacheableResponseFactory->createResponse($request, $viewData);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        return $this->renderWithDefaultViewParameters('user-reset-password.html.twig', $viewData, $response);
    }

    /**
     * @param Request $request
     * @param string $email
     * @param string $token
     *
     * @return Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     */
    public function chooseAction(Request $request, $email, $token)
    {
        $staySignedIn = $request->query->get('stay-signed-in');
        $actualToken = $this->userService->getConfirmationToken($email);
        $userResetPasswordError = $this->flashBagValues->getSingle(
            ResetPasswordActionController::FLASH_BAG_REQUEST_ERROR_KEY
        );

        $hasValidToken = $token == $actualToken;

        $viewData = [
            'email' => $email,
            'token' => $token,
            'stay_signed_in' => $staySignedIn,
            'user_reset_password_error' => $userResetPasswordError,
        ];

        $response = $this->cacheableResponseFactory->createResponse($request, $viewData);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        $view = $hasValidToken
            ? 'user-reset-password-choose.html.twig'
            : 'user-reset-password-invalid-token.html.twig';


        return $this->renderWithDefaultViewParameters($view, $viewData, $response);
    }
}
