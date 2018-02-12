<?php

namespace SimplyTestable\WebClientBundle\Services;

use SimplyTestable\WebClientBundle\Model\CacheValidatorIdentifier;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class CacheValidatorService
{
    /**
     * @var CacheValidatorHeadersService
     */
    private $cacheValidatorHeadersService;

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @param CacheValidatorHeadersService $cacheValidatorHeadersService
     * @param UserManager $userManager
     */
    public function __construct(
        CacheValidatorHeadersService $cacheValidatorHeadersService,
        UserManager $userManager
    ) {
        $this->cacheValidatorHeadersService = $cacheValidatorHeadersService;
        $this->userManager = $userManager;
    }

    /**
     * @param Request $request
     * @param array $parameters
     *
     * @return Response
     */
    public function createResponse(Request $request, array $parameters)
    {
        $cacheValidatorIdentifier = $this->createCacheValidatorIdentifier($request, $parameters);

        $cacheValidatorHeaders = $this->cacheValidatorHeadersService->get($cacheValidatorIdentifier);

        $response = new Response();
        $response->setPublic();
        $response->setEtag($cacheValidatorHeaders->getETag(), true);
        $response->setLastModified(new \DateTime($cacheValidatorHeaders->getLastModifiedDate()->format('c')));
        $response->headers->addCacheControlDirective('must-revalidate', true);

        $currentIfNoneMatch = $request->headers->get('if-none-match');
        $modifiedEtag = preg_replace('/-gzip"$/', '"', $currentIfNoneMatch);
        $request->headers->set('if-none-match', $modifiedEtag);

        $response->isNotModified($request);

        return $response;
    }

    /**
     * @param Response $response
     *
     * @return bool
     */
    public function isNotModified(Response $response)
    {
        return $response->getStatusCode() === 304;
    }

    /**
     * @param Request $request
     * @param array $parameters
     *
     * @return CacheValidatorIdentifier
     */
    private function createCacheValidatorIdentifier(Request $request, array $parameters = [])
    {
        $user = $this->userManager->getUser();

        $identifier = new CacheValidatorIdentifier();
        $identifier->setParameter('route', $request->attributes->get('_route'));
        $identifier->setParameter('user', $user->getUsername());
        $identifier->setParameter('is_logged_in', $this->userManager->isLoggedIn());

        if ($request->headers->has('accept')) {
            $identifier->setParameter('http-header-accept', $request->headers->get('accept'));
        }

        foreach ($parameters as $key => $value) {
            if (is_bool($value)) {
                $value = $value ? 'true' : 'false';
            }

            $identifier->setParameter($key, $value);
        }

        return $identifier;
    }
}
