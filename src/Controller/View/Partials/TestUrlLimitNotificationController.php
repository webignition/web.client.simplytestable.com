<?php

namespace App\Controller\View\Partials;

use App\Controller\AbstractBaseViewController;
use App\Exception\CoreApplicationRequestException;
use App\Exception\InvalidContentTypeException;
use App\Exception\InvalidCredentialsException;
use App\Services\CacheableResponseFactory;
use App\Services\DefaultViewParameters;
use App\Services\SystemUserService;
use App\Services\TestRetriever;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\RouterInterface;
use Twig_Environment;

class TestUrlLimitNotificationController extends AbstractBaseViewController
{
    private $testRetriever;

    public function __construct(
        RouterInterface $router,
        Twig_Environment $twig,
        DefaultViewParameters $defaultViewParameters,
        CacheableResponseFactory $cacheableResponseFactory,
        TestRetriever $testRetriever
    ) {
        parent::__construct($router, $twig, $defaultViewParameters, $cacheableResponseFactory);

        $this->testRetriever = $testRetriever;
    }

    /**
     * @param Request $request
     * @param int $test_id
     *
     * @return Response
     *
     * @throws CoreApplicationRequestException
     * @throws InvalidCredentialsException
     * @throws InvalidContentTypeException
     */
    public function indexAction(Request $request, int $test_id): Response
    {
        $testModel = $this->testRetriever->retrieve($test_id);

        $amendments = $testModel->getAmendments();
        if (empty($amendments)) {
            return new Response();
        }

        $isPublicUserTest = $testModel->getUser() === SystemUserService::getPublicUser()->getUsername();

        $response = $this->cacheableResponseFactory->createResponse($request, [
            'test_id' => $test_id,
            'is_public_user_test' => $isPublicUserTest,
        ]);

        if (Response::HTTP_NOT_MODIFIED === $response->getStatusCode()) {
            return $response;
        }

        $firstAmendment = $amendments[0];

        $total = (int)str_replace('plan-url-limit-reached:discovered-url-count-', '', $firstAmendment['reason']);
        $limit = $firstAmendment['constraint']['limit'];

        return $this->renderWithDefaultViewParameters(
            'Partials/Alert/Content/url-limit.html.twig',
            [
                'is_public_user_test' => $isPublicUserTest,
                'total' => $total,
                'limit' => $limit,
            ],
            $response
        );
    }
}
