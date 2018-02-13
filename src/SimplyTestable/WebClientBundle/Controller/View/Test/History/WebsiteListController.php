<?php

namespace SimplyTestable\WebClientBundle\Controller\View\Test\History;

use SimplyTestable\WebClientBundle\Controller\BaseViewController;
use SimplyTestable\WebClientBundle\Exception\CoreApplicationRequestException;
use SimplyTestable\WebClientBundle\Exception\InvalidContentTypeException;
use SimplyTestable\WebClientBundle\Exception\InvalidCredentialsException;
use SimplyTestable\WebClientBundle\Interfaces\Controller\RequiresValidUser;
use Symfony\Component\HttpFoundation\JsonResponse;

class WebsiteListController extends BaseViewController implements RequiresValidUser
{
    /**
     * @return JsonResponse
     */
    public function indexAction()
    {
        $remoteTestService = $this->container->get('simplytestable.services.remotetestservice');

        $finishedWebsites = [];

        try {
            $finishedWebsites = $remoteTestService->getFinishedWebsites();
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
