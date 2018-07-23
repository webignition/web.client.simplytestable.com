<?php

namespace App\Controller\View\User\ResetPassword;

use App\Controller\View\User\AbstractUserController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class IndexController extends AbstractUserController
{
    /**
     * @param Request $request
     *
     * @return Response
     */
    public function indexAction(Request $request)
    {
        $viewData = array_merge([
            'email' => trim($request->query->get('email')),
        ], $this->flashBagValues->get([
            'user_reset_password_error',
            'user_reset_password_confirmation',
        ]));

        $response = $this->cacheValidator->createResponse($request, $viewData);

        if ($this->cacheValidator->isNotModified($response)) {
            return $response;
        }

        return $this->renderWithDefaultViewParameters('user-reset-password.html.twig', $viewData, $response);
    }
}
