<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\ResetPassword;

use SimplyTestable\WebClientBundle\Controller\Action\User\ResetPassword\IndexController
    as ResetPasswordActionController;
use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Controller\View\User\AbstractUserController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\DefaultViewParameters;
use SimplyTestable\WebClientBundle\Services\FlashBagValues;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class ChooseController extends AbstractUserController
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
     * @param UserService $userService
     * @param FlashBagValues $flashBagValues
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        UserService $userService,
        FlashBagValues $flashBagValues
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator, $flashBagValues);

        $this->userService = $userService;
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
    public function indexAction(Request $request, $email, $token)
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

        $response = $this->cacheValidator->createResponse($request, $viewData);

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
        }

        $view = $hasValidToken
            ? 'SimplyTestableWebClientBundle:bs3/User/ResetPassword/Choose:index.html.twig'
            : 'SimplyTestableWebClientBundle:bs3/User/ResetPassword/Choose:invalid.html.twig';


        return $this->renderWithDefaultViewParameters($view, $viewData, $response);
    }
}
