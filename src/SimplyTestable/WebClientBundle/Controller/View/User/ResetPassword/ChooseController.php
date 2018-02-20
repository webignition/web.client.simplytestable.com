<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\ResetPassword;

use SimplyTestable\WebClientBundle\Controller\Action\User\ResetPassword\IndexController
    as ResetPasswordActionController;
use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidAdminCredentialsException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
use SimplyTestable\WebClientBundle\Services\CacheValidatorService;
use SimplyTestable\WebClientBundle\Services\UserService;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ChooseController extends BaseViewController implements IEFiltered
{
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
        $cacheValidatorService = $this->container->get(CacheValidatorService::class);
        $userService = $this->container->get(UserService::class);
        $flashBagValuesService = $this->container->get('SimplyTestable\WebClientBundle\Services\FlashBagValues');
        $templating = $this->container->get('templating');

        $staySignedIn = $request->query->get('stay-signed-in');
        $actualToken = $userService->getConfirmationToken($email);
        $userResetPasswordError = $flashBagValuesService->getSingle(
            ResetPasswordActionController::FLASH_BAG_REQUEST_ERROR_KEY
        );

        if ($token !== $actualToken) {
            $userResetPasswordError = 'invalid-token';
        }

        $viewData = [
            'email' => $email,
            'token' => $token,
            'stay_signed_in' => $staySignedIn,
            'user_reset_password_error' => $userResetPasswordError,
        ];

        $response = $cacheValidatorService->createResponse($request, $viewData);

        if ($cacheValidatorService->isNotModified($response)) {
            return $response;
        }

        $content = $templating->render(
            'SimplyTestableWebClientBundle:bs3/User/ResetPassword/Choose:index.html.twig',
            array_merge($this->getDefaultViewParameters(), $viewData)
        );

        $response->setContent($content);

        return $response;
    }
}
