<?php

namespace App\Tests\Functional\Services\TestOptions;

use App\Services\TaskTypeService;
use App\Services\TestOptions\RequestAdapter;
use App\Services\UserManager;
use App\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\ParameterBag;
use webignition\SimplyTestableUserModel\User;

class RequestAdapterTest extends AbstractBaseTestCase
{
    /**
     * @var RequestAdapter
     */
    private $requestAdapter;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $user = new User('user@example.com');

        $userManager = self::$container->get(UserManager::class);
        $userManager->setUser($user);

        $taskTypeService = self::$container->get(TaskTypeService::class);

        $this->requestAdapter = self::$container->get(RequestAdapter::class);

        $testOptionsParameters = self::$container->getParameter('test_options');

        $this->requestAdapter->setNamesAndDefaultValues($testOptionsParameters['names_and_default_values']);
        $this->requestAdapter->setInvertOptionKeys($testOptionsParameters['invert_option_keys']);
        $this->requestAdapter->setAvailableTaskTypes($taskTypeService->getAvailable());
        $this->requestAdapter->setAvailableFeatures($testOptionsParameters['features']);
    }

    /**
     * @dataProvider getTestOptionsTestTypesDataProvider
     *
     * @param array $requestData
     * @param array $expectedTestTypes
     */
    public function testGetTestOptionsTestTypes(array $requestData, array $expectedTestTypes)
    {
        $this->requestAdapter->setRequestData(new ParameterBag($requestData));

        $testOptions = $this->requestAdapter->getTestOptions();

        $this->assertEquals($expectedTestTypes, $testOptions->getTestTypes());
    }

    /**
     * @return array
     */
    public function getTestOptionsTestTypesDataProvider()
    {
        return [
            'empty request' => [
                'requestData' =>  [],
                'expectedTestTypes' => [],
            ],
            'html-validation: 0, css-validation: 0, link-integrity: 0' => [
                'requestData' =>  [
                    'html-validation' => 0,
                    'css-validation' => 0,
                    'link-integrity' => 0,
                ],
                'expectedTestTypes' => [],
            ],
            'html-validation: 0, css-validation: 0, link-integrity: 1' => [
                'requestData' =>  [
                    'html-validation' => 0,
                    'css-validation' => 0,
                    'link-integrity' => 1,
                ],
                'expectedTestTypes' => [
                    'Link integrity',
                ],
            ],
            'html-validation: 0, css-validation: 1, link-integrity: 0' => [
                'requestData' =>  [
                    'html-validation' => 0,
                    'css-validation' => 1,
                    'link-integrity' => 0,
                ],
                'expectedTestTypes' => [
                    'CSS validation',
                ],
            ],
            'html-validation: 0, css-validation: 1, link-integrity: 1' => [
                'requestData' =>  [
                    'html-validation' => 0,
                    'css-validation' => 1,
                    'link-integrity' => 1,
                ],
                'expectedTestTypes' => [
                    'CSS validation',
                    'Link integrity',
                ],
            ],
            'html-validation: 1, css-validation: 0, link-integrity: 0' => [
                'requestData' =>  [
                    'html-validation' => 1,
                    'css-validation' => 0,
                    'link-integrity' => 0,
                ],
                'expectedTestTypes' => [
                    'HTML validation',
                ],
            ],
            'html-validation: 1, css-validation: 0, link-integrity: 1' => [
                'requestData' =>  [
                    'html-validation' => 1,
                    'css-validation' => 0,
                    'link-integrity' => 1,
                ],
                'expectedTestTypes' => [
                    'HTML validation',
                    'Link integrity',
                ],
            ],
            'html-validation: 1, css-validation: 1, link-integrity: 0' => [
                'requestData' =>  [
                    'html-validation' => 1,
                    'css-validation' => 1,
                    'link-integrity' => 0,
                ],
                'expectedTestTypes' => [
                    'HTML validation',
                    'CSS validation',
                ],
            ],
            'html-validation: 1, css-validation: 1, link-integrity: 1' => [
                'requestData' =>  [
                    'html-validation' => 1,
                    'css-validation' => 1,
                    'link-integrity' => 1,
                ],
                'expectedTestTypes' => [
                    'HTML validation',
                    'CSS validation',
                    'Link integrity',
                ],
            ],
        ];
    }

    /**
     * @dataProvider getTestOptionsFeatureOptionsDataProvider
     *
     * @param array $requestData
     * @param array $expectedFeatureOptionsCollection
     */
    public function testGetTestOptionsFeatureOptions(array $requestData, array $expectedFeatureOptionsCollection)
    {
        $this->requestAdapter->setRequestData(new ParameterBag($requestData));

        $testOptions = $this->requestAdapter->getTestOptions();

        $features = $testOptions->getFeatures();

        if (count($expectedFeatureOptionsCollection)) {
            $this->assertCount(count($expectedFeatureOptionsCollection), $features);

            foreach ($features as $feature) {
                $expectedFeatureOptions = $expectedFeatureOptionsCollection[$feature];

                $featureOptions = $testOptions->getFeatureOptions($feature);

                $this->assertEquals($expectedFeatureOptions, $featureOptions);
            }
        } else {
            $this->assertSame([], $features);
        }
    }

    /**
     * @return array
     */
    public function getTestOptionsFeatureOptionsDataProvider()
    {
        return [
            'empty request' => [
                'requestData' =>  [],
                'expectedFeatureOptionsCollection' => [
                    'http-authentication' => [],
                    'cookies' => [],
                ],
            ],
            'no feature options' => [
                'requestData' =>  [
                    'html-validation' => 1,
                ],
                'expectedFeatureOptionsCollection' => [
                    'http-authentication' => [],
                    'cookies' => [],
                ],
            ],
            'with feature options' => [
                'requestData' =>  [
                    'http-auth-username' => 'user',
                    'http-auth-password' => 'pass',
                    'cookies' => [
                        [
                            'name' => 'cookie-name-1',
                            'value' => 'cookie-value-1',
                        ],
                        [
                            'name' => 'cookie-name-2',
                            'value' => 'cookie-value-2',
                        ],
                    ],
                ],
                'expectedFeatureOptionsCollection' => [
                    'http-authentication' => [
                        'http-auth-username' => 'user',
                        'http-auth-password' => 'pass',
                    ],
                    'cookies' => [
                        'cookies' => [
                            [
                                'name' => 'cookie-name-1',
                                'value' => 'cookie-value-1',
                            ],
                            [
                                'name' => 'cookie-name-2',
                                'value' => 'cookie-value-2',
                            ],
                        ],
                    ],
                ],
            ],
            'empty cookies are removed' => [
                'requestData' =>  [
                    'cookies' => [
                        [
                            'name' => 'cookie-name-1',
                            'value' => 'cookie-value-1',
                        ],
                        [
                            'name' => '',
                            'value' => null,
                        ],
                    ],
                ],
                'expectedFeatureOptionsCollection' => [
                    'http-authentication' => [],
                    'cookies' => [
                        'cookies' => [
                            [
                                'name' => 'cookie-name-1',
                                'value' => 'cookie-value-1',
                            ],
                        ],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getTestOptionsTestTypeOptionsDataProvider
     *
     * @param array $requestData
     * @param array $expectedTestTypeOptionsCollection
     */
    public function testGetTestOptionsTestTypeOptions(array $requestData, array $expectedTestTypeOptionsCollection)
    {
        $this->requestAdapter->setRequestData(new ParameterBag($requestData));

        $testOptions = $this->requestAdapter->getTestOptions();

        if (count($expectedTestTypeOptionsCollection)) {
            foreach ($expectedTestTypeOptionsCollection as $testTypeKey => $expectedTestTypeOptions) {
                $testTypeOptions = $testOptions->getTestTypeOptions($testTypeKey);

                $this->assertEquals($expectedTestTypeOptions, $testTypeOptions);
            }
        } else {
            $this->assertSame(
                [
                    'http-authentication' => 1,
                    'cookies' => 1,
                ],
                $testOptions->__toKeyArray()
            );
        }
    }

    /**
     * @return array
     */
    public function getTestOptionsTestTypeOptionsDataProvider()
    {
        return [
            'empty request' => [
                'requestData' =>  [],
                'expectedTestTypeOptionsCollection' => [],
            ],
            'no test type options' => [
                'requestData' =>  [
                    'html-validation' => 1,
                    'css-validation' => 1,
                    'js-static-analysis' => 1,
                    'link-integrity' => 1,
                ],
                'expectedTestTypeOptionsCollection' => [
                    'html-validation' => [],
                    'css-validation' => [],
                    'js-static-analysis' => [],
                    'link-integrity' => [],
                ],
            ],
            'has test type options' => [
                'requestData' =>  [
                    'html-validation' => 1,
                    'css-validation' => 1,
                    'js-static-analysis' => 1,
                    'link-integrity' => 1,
                    'css-validation-ignore-warnings' => 1,
                    'css-validation-ignore-common-cdns' => 0,
                    'css-validation-vendor-extensions' => 'warn',
                    'css-validation-domains-to-ignore' => [],
                ],
                'expectedTestTypeOptionsCollection' => [
                    'html-validation' => [],
                    'css-validation' => [
                        'css-validation-ignore-warnings' => 1,
                        'css-validation-ignore-common-cdns' => 0,
                        'css-validation-vendor-extensions' => 'warn',
                        'css-validation-domains-to-ignore' => [],
                    ],
                    'link-integrity' => [],
                ],
            ],
        ];
    }
}
