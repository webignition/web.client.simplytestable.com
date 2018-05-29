<?php

namespace Tests\WebClientBundle\Unit\Services\Request\Factory\User;

use SimplyTestable\WebClientBundle\Request\User\SignUpRequest;
use SimplyTestable\WebClientBundle\Services\Request\Factory\User\SignUpRequestFactory;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class SignUpRequestFactoryTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @dataProvider createDataProvider
     *
     * @param RequestStack $requestStack
     * @param string $expectedEmail
     * @param string $expectedPassword
     * @param string $expectedPlan
     */
    public function testCreate(
        RequestStack $requestStack,
        $expectedEmail,
        $expectedPassword,
        $expectedPlan
    ) {
        $signUpRequestFactory = new SignUpRequestFactory($requestStack);

        $signUpRequest = $signUpRequestFactory->create();

        $this->assertEquals($expectedEmail, $signUpRequest->getEmail());
        $this->assertEquals($expectedPassword, $signUpRequest->getPassword());
        $this->assertEquals($expectedPlan, $signUpRequest->getPlan());
    }

    /**
     * @return array
     */
    public function createDataProvider()
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

    /**
     * @param Request $request
     *
     * @return RequestStack
     */
    private function createRequestStack(Request $request)
    {
        $requestStack = new RequestStack();
        $requestStack->push($request);

        return $requestStack;
    }
}
