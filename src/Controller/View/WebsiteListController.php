<?php

namespace App\Controller\View;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\RemoteTestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class WebsiteListController
{
    /**
     * @var Response|RedirectResponse|JsonResponse
     */
    protected $response;

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
     * @return bool
     */
    public function hasResponse()
    {
        return !empty($this->response);
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
