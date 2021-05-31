<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services\Request\Factory\User;

use App\Request\User\SignInRequest;
use App\Services\Request\Factory\User\SignInRequestFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class SignInRequestFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        RequestStack $requestStack,
        string $expectedEmail,
        string $expectedPassword,
        string $expectedRedirect,
        bool $expectedStaySignedIn
    ) {
        $signInRequestFactory = new SignInRequestFactory($requestStack);

        $signInRequest = $signInRequestFactory->create();

        $this->assertEquals($expectedEmail, $signInRequest->getEmail());
        $this->assertEquals($expectedPassword, $signInRequest->getPassword());
        $this->assertEquals($expectedRedirect, $signInRequest->getRedirect());
        $this->assertEquals($expectedStaySignedIn, $signInRequest->getStaySignedIn());
    }

    public function createDataProvider(): array
    {
        return [
            'empty request' => [
                'requestStack' => $this->createRequestStack(new Request()),
                'expectedEmail' => '',
                'expectedPassword' => '',
                'expectedRedirect' => '',
                'expectedStaySignedIn' => false,
            ],
            'non-empty request; stay-signed-in empty' => [
                'requestStack' => $this->createRequestStack(new Request([], [
                    SignInRequest::PARAMETER_EMAIL => 'user@example.com',
                    SignInRequest::PARAMETER_PASSWORD => 'password value',
                    SignInRequest::PARAMETER_REDIRECT => 'foo',
                    SignInRequest::PARAMETER_STAY_SIGNED_IN => '',
                ])),
                'expectedEmail' => 'user@example.com',
                'expectedPassword' => 'password value',
                'expectedRedirect' => 'foo',
                'expectedStaySignedIn' => false,
            ],
            'non-empty request; stay-signed-in zero' => [
                'requestStack' => $this->createRequestStack(new Request([], [
                    SignInRequest::PARAMETER_EMAIL => 'user@example.com',
                    SignInRequest::PARAMETER_PASSWORD => 'password value',
                    SignInRequest::PARAMETER_REDIRECT => 'foo',
                    SignInRequest::PARAMETER_STAY_SIGNED_IN => 0,
                ])),
                'expectedEmail' => 'user@example.com',
                'expectedPassword' => 'password value',
                'expectedRedirect' => 'foo',
                'expectedStaySignedIn' => false,
            ],
            'non-empty request; stay-signed-in string zero' => [
                'requestStack' => $this->createRequestStack(new Request([], [
                    SignInRequest::PARAMETER_EMAIL => 'user@example.com',
                    SignInRequest::PARAMETER_PASSWORD => 'password value',
                    SignInRequest::PARAMETER_REDIRECT => 'foo',
                    SignInRequest::PARAMETER_STAY_SIGNED_IN => '0',
                ])),
                'expectedEmail' => 'user@example.com',
                'expectedPassword' => 'password value',
                'expectedRedirect' => 'foo',
                'expectedStaySignedIn' => false,
            ],
            'non-empty request; stay-signed-in one' => [
                'requestStack' => $this->createRequestStack(new Request([], [
                    SignInRequest::PARAMETER_EMAIL => 'user@example.com',
                    SignInRequest::PARAMETER_PASSWORD => 'password value',
                    SignInRequest::PARAMETER_REDIRECT => 'foo',
                    SignInRequest::PARAMETER_STAY_SIGNED_IN => 1,
                ])),
                'expectedEmail' => 'user@example.com',
                'expectedPassword' => 'password value',
                'expectedRedirect' => 'foo',
                'expectedStaySignedIn' => true,
            ],
            'non-empty request; stay-signed-in string one' => [
                'requestStack' => $this->createRequestStack(new Request([], [
                    SignInRequest::PARAMETER_EMAIL => 'user@example.com',
                    SignInRequest::PARAMETER_PASSWORD => 'password value',
                    SignInRequest::PARAMETER_REDIRECT => 'foo',
                    SignInRequest::PARAMETER_STAY_SIGNED_IN => '1',
                ])),
                'expectedEmail' => 'user@example.com',
                'expectedPassword' => 'password value',
                'expectedRedirect' => 'foo',
                'expectedStaySignedIn' => true,
            ],
        ];
    }

    private function createRequestStack(Request $request)
    {
        $requestStack = new RequestStack();
        $requestStack->push($request);

        return $requestStack;
    }
}
