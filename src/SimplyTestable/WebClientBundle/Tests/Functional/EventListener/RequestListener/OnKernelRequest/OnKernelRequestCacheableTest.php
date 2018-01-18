<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\EventListener\RequestListener\OnKernelRequest;

use SimplyTestable\WebClientBundle\Entity\CacheValidatorHeaders;
use SimplyTestable\WebClientBundle\Model\CacheValidatorIdentifier;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class OnKernelRequestCacheableTest extends AbstractOnKernelRequestTest
{
    const CONTROLLER_ACTION =
        'SimplyTestable\WebClientBundle\Controller\View\User\SignInController::indexAction';
    const CONTROLLER_ROUTE = 'view_user_signin_index';

    /**
     * @dataProvider dataProvider
     *
     * @param Request $request
     * @param array $requestHeaders
     * @param array $cacheValidatorIdentifierParameters
     * @param \DateTime $cacheValidatorHeadersLastModified
     * @param bool $expectedHasResponse
     *
     * @throws \Exception
     * @throws \SimplyTestable\WebClientBundle\Exception\WebResourceException
     */
    public function testOnKernelRequest(
        Request $request,
        array $requestHeaders,
        array $cacheValidatorIdentifierParameters,
        $cacheValidatorHeadersLastModified,
        $expectedHasResponse
    ) {
        if (!empty($cacheValidatorIdentifierParameters)) {
            $entityManager = $this->container->get('doctrine.orm.entity_manager');

            $cacheValidatorIdentifier = new CacheValidatorIdentifier();
            foreach ($cacheValidatorIdentifierParameters as $key => $value) {
                $cacheValidatorIdentifier->setParameter($key, $value);
            }

            $cacheValidatorHeaders = new CacheValidatorHeaders();
            $cacheValidatorHeaders->setIdentifier($cacheValidatorIdentifier);
            $cacheValidatorHeaders->setLastModifiedDate($cacheValidatorHeadersLastModified);

            $entityManager->persist($cacheValidatorHeaders);
            $entityManager->flush();
        }

        foreach ($requestHeaders as $key => $value) {
            $request->headers->set($key, $value);
        }

        $event = $this->createGetResponseEvent(
            $request,
            self::CONTROLLER_ACTION,
            self::CONTROLLER_ROUTE,
            null,
            HttpKernelInterface::MASTER_REQUEST
        );

        $this->requestListener->onKernelRequest($event);

        $response = $event->getResponse();

        if ($expectedHasResponse) {
            $this->assertInstanceOf(Response::class, $response);
            $this->assertEquals(304, $response->getStatusCode());
        } else {
            $this->assertNull($response);
        }
    }

    /**
     * @return array
     */
    public function dataProvider()
    {
        return [
            'modified' => [
                'request' => new Request(),
                'requestHeaders' => [],
                'cacheValidatorIdentifierParameters' => [],
                'cacheValidatorHeadersLastModified' =>  null,
                'expectedHasResponse' => false,
            ],
            'not modified' => [
                'request' => new Request(),
                'requestHeaders' => [
                    'if-modified-since' => 'Thu, 18 Jan 2019 12:56:32 GMT',
                    'x-cache-validator-lastmodified' => 'Thu, 18 Jan 2018 12:56:32 GMT',
                ],
                'cacheValidatorIdentifierParameters' => [
                    'route' => 'view_user_signin_index',
                    'user' => 'public',
                    'is_logged_in' => false,
                    'email' => null,
                    'user_signin_error' => '',
                    'user_signin_confirmation' => '',
                    'redirect' => null,
                    'stay_signed_in' => null,
                ],
                'cacheValidatorHeadersLastModified' =>  new \DateTime('2015-05-08 21:00:22'),
                'expectedHasResponse' => true,
            ],
            'not modified; with accept header' => [
                'request' => new Request(),
                'requestHeaders' => [
                    'if-modified-since' => 'Thu, 18 Jan 2019 12:56:32 GMT',
                    'x-cache-validator-lastmodified' => 'Thu, 18 Jan 2018 12:56:32 GMT',
                    'accept' => 'foo',
                ],
                'cacheValidatorIdentifierParameters' => [
                    'route' => 'view_user_signin_index',
                    'user' => 'public',
                    'is_logged_in' => false,
                    'email' => null,
                    'user_signin_error' => '',
                    'user_signin_confirmation' => '',
                    'redirect' => null,
                    'stay_signed_in' => null,
                ],
                'cacheValidatorHeadersLastModified' =>  new \DateTime('2015-05-08 21:00:22'),
                'expectedHasResponse' => true,
            ],
        ];
    }
}
