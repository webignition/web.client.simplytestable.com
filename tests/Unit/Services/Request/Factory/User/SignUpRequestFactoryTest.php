<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services\Request\Factory\User;

use App\Request\User\SignUpRequest;
use App\Services\Request\Factory\User\SignUpRequestFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class SignUpRequestFactoryTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider createDataProvider
     */
    public function testCreate(
        RequestStack $requestStack,
        string $expectedEmail,
        string $expectedPassword,
        string $expectedPlan
    ) {
        $signUpRequestFactory = new SignUpRequestFactory($requestStack);

        $signUpRequest = $signUpRequestFactory->create();

        $this->assertEquals($expectedEmail, $signUpRequest->getEmail());
        $this->assertEquals($expectedPassword, $signUpRequest->getPassword());
        $this->assertEquals($expectedPlan, $signUpRequest->getPlan());
    }

    public function createDataProvider(): array
    {
        return [
            'empty request' => [
                'requestStack' => $this->createRequestStack(new Request()),
                'expectedEmail' => '',
                'expectedPassword' => '',
                'expectedPlan' => '',
            ],
            'non-empty request' => [
                'requestStack' => $this->createRequestStack(new Request([], [
                    SignUpRequest::PARAMETER_EMAIL => 'user@example.com',
                    SignUpRequest::PARAMETER_PASSWORD => 'password value',
                    SignUpRequest::PARAMETER_PLAN => 'personal',
                ])),
                'expectedEmail' => 'user@example.com',
                'expectedPassword' => 'password value',
                'expectedPlan' => 'personal',
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
