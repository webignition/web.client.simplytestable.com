<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services;

use Mockery\MockInterface;
use App\Request\User\SignInRequest;
use App\Request\User\SignUpRequest;
use App\Services\RedirectResponseFactory;
use Symfony\Component\Routing\RouterInterface;

class RedirectResponseFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createSignInRedirectResponseDataProvider
     */
    public function testCreateSignInRedirectResponse(
        string $email,
        string $redirect,
        bool $staySignedIn,
        array $expectedRouteParameters
    ) {
        /* @var MockInterface|RouterInterface $router */
        $router = \Mockery::mock(RouterInterface::class);
        $router
            ->shouldReceive('generate')
            ->withArgs([
                'sign_in_render',
                $expectedRouteParameters
            ])
            ->andReturn('http://example.com/');

        $redirectResponseFactory = new RedirectResponseFactory($router);

        $signInRequest = new SignInRequest($email, '', $redirect, $staySignedIn);

        $redirectResponseFactory->createSignInRedirectResponse($signInRequest);
    }

    public function createSignInRedirectResponseDataProvider(): array
    {
        return [
            'no email, no redirect, stay-signed-in=false' => [
                'email' => '',
                'redirect' => '',
                'staySignedIn' => false,
                'expectedRouteParameters' => [
                    'stay-signed-in' => 0,
                ],
            ],
            'email, redirect, stay-signed-in=true' => [
                'email' => 'user@example.com',
                'redirect' => 'foo',
                'staySignedIn' => true,
                'expectedRouteParameters' => [
                    'email' => 'user@example.com',
                    'redirect' => 'foo',
                    'stay-signed-in' => 1,
                ],
            ],
        ];
    }

    /**
     * @dataProvider createSignUpRedirectResponseDataProvider
     */
    public function testCreateSignUpRedirectResponse(string $email, string $plan, array $expectedRouteParameters)
    {
        /* @var MockInterface|RouterInterface $router */
        $router = \Mockery::mock(RouterInterface::class);
        $router
            ->shouldReceive('generate')
            ->withArgs([
                'view_user_sign_up_request',
                $expectedRouteParameters
            ])
            ->andReturn('http://example.com/');

        $redirectResponseFactory = new RedirectResponseFactory($router);

        $signUpRequest = new SignUpRequest($email, '', $plan);

        $redirectResponseFactory->createSignUpRedirectResponse($signUpRequest);
    }

    public function createSignUpRedirectResponseDataProvider(): array
    {
        return [
            'no email, plan' => [
                'email' => '',
                'plan' => 'personal',
                'expectedRouteParameters' => [
                    'plan' => 'personal',
                ],
            ],
            'email, plan' => [
                'email' => 'user@example.com',
                'plan' => 'personal',
                'expectedRouteParameters' => [
                    'email' => 'user@example.com',
                    'plan' => 'personal',
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        $this->addToAssertionCount(
            \Mockery::getContainer()->mockery_getExpectationCount()
        );

        \Mockery::close();
    }
}
