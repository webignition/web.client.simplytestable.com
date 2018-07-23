<?php

namespace App\Controller\View\Test\History;

use App\Controller\AbstractBaseViewController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Interfaces\Controller\RequiresValidUser;
use App\Services\CacheValidatorService;
use App\Services\DefaultViewParameters;
use App\Services\RemoteTestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class WebsiteListController extends AbstractBaseViewController implements RequiresValidUser
{
    /**
     * @var RemoteTestService
     */
    private $remoteTestService;

    /**
     * @param RouterInterface $router
     * @param Twig_Environment $twig
     * @param DefaultViewParameters $defaultViewParameters
     * @param CacheValidatorService $cacheValidator
     * @param RemoteTestService $remoteTestService
     */
    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheValidatorService $cacheValidator,
        RemoteTestService $remoteTestService
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheValidator);

        $this->remoteTestService = $remoteTestService;
    }

    /**
     * @return JsonResponse
     */
    public function indexAction()
    {
        if ($this->hasResponse()) {
            return $this->response;
        }

        $finishedWebsites = [];

        try {
            $finishedWebsites = $this->remoteTestService->getFinishedWebsites();
        } catch (CoreApplicationRequestException $coreApplicationRequestException) {
            // Don't care
        } catch (InvalidContentTypeException $invalidContentTypeException) {
            // Don't care
        } catch (InvalidCredentialsException $invalidCredentialsException) {
            // Don't care
        }

        return new JsonResponse($finishedWebsites);
    }
}
