<?php

namespace App\Controller\View\Test;

use App\Controller\AbstractBaseViewController;
use App\Interfaces\Controller\Test\RequiresValidOwner;
use App\Services\CacheValidatorService;
use App\Services\DefaultViewParameters;
use App\Services\UrlViewValuesService;
use App\Services\UserManager;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

abstract class AbstractRequiresValidOwnerController extends AbstractBaseViewController implements RequiresValidOwner
{
    /**
     * @var UrlViewValuesService
     */
    protected $urlViewValues;

    /**
     * @var UserManager
     */
    protected $userManager;

    /**
     * @var Session
     */
    protected $session;

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param UrlViewValuesService $urlViewValues
     * @param UserManager $userManager
     * @param SessionInterface $session
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        UrlViewValuesService $urlViewValues,
        UserManager $userManager,
        SessionInterface $session
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator);

        $this->urlViewValues = $urlViewValues;
        $this->userManager = $userManager;
        $this->session = $session;
    }

    /**
     * {@inheritdoc}
     */
    public function getInvalidOwnerResponse(Request $request)
    {
        if ($this->userManager->isLoggedIn()) {
            return $this->renderWithDefaultViewParameters(
                'test-results-not-authorised.html.twig',
                [
                    'test_id' => $request->attributes->get('test_id'),
                    'website' => $this->urlViewValues->create($request->attributes->get('website')),
                ]
            );
        }

        $redirectParameters = json_encode([
            'route' => 'view_test_progress',
            'parameters' => [
                'website' => $request->attributes->get('website'),
                'test_id' => $request->attributes->get('test_id')
            ]
        ]);

        $this->session->getFlashBag()->set('user_signin_error', 'test-not-logged-in');

        return new RedirectResponse($this->generateUrl(
            'view_user_sign_in',
            [
                'redirect' => base64_encode($redirectParameters)
            ]
        ));
    }
}
