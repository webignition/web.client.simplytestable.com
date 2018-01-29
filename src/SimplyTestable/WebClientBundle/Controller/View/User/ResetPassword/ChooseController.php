<?php

namespace SimplyTestable\WebClientBundle\Controller\View\User\ResetPassword;

use SimplyTestable\WebClientBundle\Controller\Action\User\ResetPassword\IndexController;
use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationAdminRequestException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\IEFiltered;
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
     * @throws CoreApplicationAdminRequestException
     */
    public function indexAction(Request $request, $email, $token)
    {
        $cacheValidatorService = $this->container->get('simplytestable.services.cachevalidator');
        $userService = $this->container->get('simplytestable.services.userservice');
        $flashBagValuesService = $this->container->get('simplytestable.services.flashbagvalues');
        $templating = $this->container->get('templating');

        $staySignedIn = $request->query->get('stay-signed-in');
        $actualToken = $userService->getConfirmationToken($email);
        $userResetPasswordError = $flashBagValuesService->getSingle(IndexController::FLASH_BAG_REQUEST_ERROR_KEY);

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
