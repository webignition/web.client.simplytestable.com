<?php

namespace App\Controller\View;

use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Interfaces\Controller\RequiresValidUser;
use App\Services\RemoteTestService;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class WebsiteListController implements RequiresValidUser
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
     * @param Response $response
     */
    public function setResponse(Response $response)
    {
        $this->response = $response;
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
