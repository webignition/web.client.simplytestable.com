<?php

namespace App\Controller\View;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\RemoteTestService;
use Symfony\Component\HttpFoundation\JsonResponse;

class WebsiteListController
{
    /**
     * @var RemoteTestService
     */
    private $remoteTestService;

    /**
     * @param RemoteTestService $remoteTestService
     */
    public function __construct(RemoteTestService $remoteTestService)
    {
        $this->remoteTestService = $remoteTestService;
    }

    /**
     * @return JsonResponse
     */
    public function indexAction()
    {
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
