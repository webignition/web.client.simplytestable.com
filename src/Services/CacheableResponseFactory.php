<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use webignition\SimplyTestableUserManagerInterface\UserManagerInterface;
use SimplyTestable\PageCacheBundle\Services\CacheableResponseFactory as BaseCacheableResponseFactory;

class CacheableResponseFactory
{
    /**
     * @var BaseCacheableResponseFactory
     */
    private $baseCacheableResponseFactory;

    /**
     * @var UserManagerInterface
     */
    private $userManager;

    public function __construct(
        BaseCacheableResponseFactory $baseCacheableResponseFactory,
        UserManagerInterface $userManager
    ) {
        $this->baseCacheableResponseFactory = $baseCacheableResponseFactory;
        $this->userManager = $userManager;
    }

    public function createResponse(Request $request, array $parameters): Response
    {
        $user = $this->userManager->getUser();

        $userParameters = [
            'user' => $user->getUsername(),
            'is_logged_in' => $this->userManager->isLoggedIn(),
        ];

        return $this->baseCacheableResponseFactory->createResponse(
            $request,
            array_merge($userParameters, $parameters)
        );
    }
}
