<?php

namespace App\Controller\User;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidAdminCredentialsException;
use App\Exception\InvalidContentTypeException;
use App\Exception\Mail\Configuration\Exception as MailConfigurationException;
use App\Request\User\SignInRequest;
use App\Services\FlashBagValues;
use App\Services\Mailer;
use App\Services\RedirectResponseFactory;
use App\Services\Request\Factory\User\SignInRequestFactory;
use App\Services\Request\Validator\User\SignInRequestValidator;
use App\Services\SystemUserService;
use App\Services\UserManager;
use App\Services\UserService;
use App\Services\ViewRenderService;
use Postmark\Models\PostmarkException;
use SimplyTestable\PageCacheBundle\Services\CacheableResponseFactory;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\RouterInterface;
use webignition\SimplyTestableUserModel\User;

class SignInController
{
    const FLASH_SIGN_IN_ERROR_FIELD_KEY = 'user_signin_error_field';
    const FLASH_SIGN_IN_ERROR_STATE_KEY = 'user_signin_error_state';
    const FLASH_SIGN_IN_ERROR_STATE_INVALID_USER = 'invalid-user';
    const FLASH_SIGN_IN_ERROR_STATE_AUTHENTICATION_FAILURE = 'authentication-failure';
    const FLASH_SIGN_IN_ERROR_STATE_USER_NOT_ENABLED = 'user-not-enabled';

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var ViewRenderService
     */
    private $viewRenderService;

    /**
     * @var RouterInterface
     */
    private $router;

    public function __construct(
        UserManager $userManager,
        ViewRenderService $viewRenderService,
        RouterInterface $router
    ) {
        $this->userManager = $userManager;
        $this->viewRenderService = $viewRenderService;
        $this->router = $router;
    }

    /**
     * @param FlashBagValues $flashBagValues
     * @param CacheableResponseFactory $cacheableResponseFactory
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function renderAction(
        FlashBagValues $flashBagValues,
        CacheableResponseFactory $cacheableResponseFactory,
        Request $request
    ) {
        $user = $this->userManager->getUser();

        if (!SystemUserService::isPublicUser($user)) {
            return new RedirectResponse($this->router->generate('view_dashboard'));
        }

        $requestData = $request->query;
        $email = $requestData->get(SignInRequest::PARAMETER_EMAIL);
        $errorField = $flashBagValues->getSingle('user_signin_error_field');
        $errorState = $flashBagValues->getSingle('user_signin_error_state');
        $userSignInError = $flashBagValues->getSingle('user_signin_error');
        $userSignInConfirmation = $flashBagValues->getSingle('user_signin_confirmation');
        $redirect = $requestData->get(SignInRequest::PARAMETER_REDIRECT);
        $staySignedIn = $requestData->get(SignInRequest::PARAMETER_STAY_SIGNED_IN);

        $selectedField = empty($email) || SignInRequest::PARAMETER_EMAIL === $errorField || !empty($userSignInError)
            ? SignInRequest::PARAMETER_EMAIL
            : SignInRequest::PARAMETER_PASSWORD;

        $viewData = [
            'email' => $email,
            'error_field' => $errorField,
            'error_state' => $errorState,
            'user_signin_error' => $userSignInError,
            'user_signin_confirmation' => $userSignInConfirmation,
            'redirect' => $redirect,
            'stay_signed_in' => $staySignedIn,
            'selected_field' => $selectedField,
        ];

        $response = $cacheableResponseFactory->createResponse($request, $viewData);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        return $this->viewRenderService->renderResponseWithDefaultViewParameters(
            'user-sign-in.html.twig',
            $viewData,
            $response
        );
    }

    /**
     * @param Mailer $mailer
     * @param SignInRequestFactory $signInRequestFactory
     * @param SignInRequestValidator $signInRequestValidator
     * @param FlashBagInterface $flashBag
     * @param RedirectResponseFactory $redirectResponseFactory
     * @param UserService $userService
     *
     * @return RedirectResponse
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidAdminCredentialsException
     * @throws InvalidContentTypeException
     * @throws MailConfigurationException
     * @throws PostmarkException
     */
    public function signInAction(
        Mailer $mailer,
        SignInRequestFactory $signInRequestFactory,
        SignInRequestValidator $signInRequestValidator,
        FlashBagInterface $flashBag,
        RedirectResponseFactory $redirectResponseFactory,
        UserService $userService
    ) {
        $signInRequest = $signInRequestFactory->create();
        $signInRequestValidator->validate($signInRequest);

        $email = $signInRequest->getEmail();
        $password = $signInRequest->getPassword();
        $staySignedIn = $signInRequest->getStaySignedIn();
        $redirect = $signInRequest->getRedirect();

        $signInRedirectResponse = $redirectResponseFactory->createSignInRedirectResponse($signInRequest);

        if (false === $signInRequestValidator->getIsValid()) {
            $flashBag->set(self::FLASH_SIGN_IN_ERROR_FIELD_KEY, $signInRequestValidator->getInvalidFieldName());
            $flashBag->set(self::FLASH_SIGN_IN_ERROR_STATE_KEY, $signInRequestValidator->getInvalidFieldState());

            return $signInRedirectResponse;
        }

        $user = new User($email, $password);
        $this->userManager->setUser($user);

        if (!$userService->authenticate()) {
            if (!$userService->exists()) {
                $this->userManager->clearSessionUser();

                $flashBag->set(self::FLASH_SIGN_IN_ERROR_FIELD_KEY, SignInRequest::PARAMETER_EMAIL);
                $flashBag->set(self::FLASH_SIGN_IN_ERROR_STATE_KEY, self::FLASH_SIGN_IN_ERROR_STATE_INVALID_USER);

                return $signInRedirectResponse;
            }

            if ($userService->isEnabled()) {
                $this->userManager->clearSessionUser();

                $flashBag->set(self::FLASH_SIGN_IN_ERROR_FIELD_KEY, SignInRequest::PARAMETER_PASSWORD);
                $flashBag->set(
                    self::FLASH_SIGN_IN_ERROR_STATE_KEY,
                    self::FLASH_SIGN_IN_ERROR_STATE_AUTHENTICATION_FAILURE
                );

                return $signInRedirectResponse;
            }

            $this->userManager->clearSessionUser();

            $token = $userService->getConfirmationToken($email);
            $mailer->sendSignUpConfirmationToken($email, $token);

            $flashBag->set(self::FLASH_SIGN_IN_ERROR_FIELD_KEY, SignInRequest::PARAMETER_EMAIL);
            $flashBag->set(self::FLASH_SIGN_IN_ERROR_STATE_KEY, self::FLASH_SIGN_IN_ERROR_STATE_USER_NOT_ENABLED);

            return $signInRedirectResponse;
        }

        if (!$userService->isEnabled()) {
            $this->userManager->clearSessionUser();

            $token = $userService->getConfirmationToken($email);
            $mailer->sendSignUpConfirmationToken($email, $token);

            $flashBag->set(self::FLASH_SIGN_IN_ERROR_FIELD_KEY, SignInRequest::PARAMETER_EMAIL);
            $flashBag->set(self::FLASH_SIGN_IN_ERROR_STATE_KEY, self::FLASH_SIGN_IN_ERROR_STATE_USER_NOT_ENABLED);

            return $signInRedirectResponse;
        }

        $response = $this->createPostSignInRedirectResponse($redirect);

        if ($staySignedIn) {
            $response->headers->setCookie($this->userManager->createUserCookie());
        }

        return $response;
    }

    /**
     * @param string $requestRedirect
     *
     * @return RedirectResponse
     */
    private function createPostSignInRedirectResponse($requestRedirect)
    {
        $dashboardRedirectResponse = new RedirectResponse($this->router->generate('view_dashboard'));
        $redirectValues = json_decode(base64_decode($requestRedirect), true);

        if (!is_array($redirectValues) || !isset($redirectValues['route'])) {
            return $dashboardRedirectResponse;
        }

        $routeName = $redirectValues['route'];
        $routeParameters = isset($redirectValues['parameters']) ? $redirectValues['parameters'] : [];

        try {
            return new RedirectResponse($this->router->generate($routeName, $routeParameters));
        } catch (\Exception $exception) {
        }

        return $dashboardRedirectResponse;
    }
}
