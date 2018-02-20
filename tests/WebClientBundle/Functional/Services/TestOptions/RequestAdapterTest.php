<?php

namespace Tests\WebClientBundle\Functional\Services\TestOptions;

use SimplyTestable\WebClientBundle\Model\User;
use SimplyTestable\WebClientBundle\Services\TestOptions\RequestAdapter;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\HttpFoundation\ParameterBag;

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

        $taskTypeService = $this->container->get('SimplyTestable\WebClientBundle\Services\TaskTypeService');
        $taskTypeService->setEarlyAccessUsers([
            $user,
        ]);
        $taskTypeService->setUser($user);
        $taskTypeService->setUserIsAuthenticated();

        $this->requestAdapter = $this->container->get(RequestAdapter::class);

        $testOptionsParameters = $this->container->getParameter('test_options');

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
            'html-validation: 0, css-validation: 0, js-static-analysis: 0, link-integrity: 0' => [
                'requestData' =>  [
                    'html-validation' => 0,
                    'css-validation' => 0,
                    'js-static-analysis' => 0,
                    'link-integrity' => 0,
                ],
                'expectedTestTypes' => [],
            ],
            'html-validation: 0, css-validation: 0, js-static-analysis: 0, link-integrity: 1' => [
                'requestData' =>  [
                    'html-validation' => 0,
                    'css-validation' => 0,
                    'js-static-analysis' => 0,
                    'link-integrity' => 1,
                ],
                'expectedTestTypes' => [
                    'Link integrity',
                ],
            ],
            'html-validation: 0, css-validation: 0, js-static-analysis: 1, link-integrity: 0' => [
                'requestData' =>  [
                    'html-validation' => 0,
                    'css-validation' => 0,
                    'js-static-analysis' => 1,
                    'link-integrity' => 0,
                ],
                'expectedTestTypes' => [
                    'JS static analysis',
                ],
            ],
            'html-validation: 0, css-validation: 0, js-static-analysis: 1, link-integrity: 1' => [
                'requestData' =>  [
                    'html-validation' => 0,
                    'css-validation' => 0,
                    'js-static-analysis' => 1,
                    'link-integrity' => 1,
                ],
                'expectedTestTypes' => [
                    'JS static analysis',
                    'Link integrity',
                ],
            ],
            'html-validation: 0, css-validation: 1, js-static-analysis: 0, link-integrity: 0' => [
                'requestData' =>  [
                    'html-validation' => 0,
                    'css-validation' => 1,
                    'js-static-analysis' => 0,
                    'link-integrity' => 0,
                ],
                'expectedTestTypes' => [
                    'CSS validation',
                ],
            ],
            'html-validation: 0, css-validation: 1, js-static-analysis: 0, link-integrity: 1' => [
                'requestData' =>  [
                    'html-validation' => 0,
                    'css-validation' => 1,
                    'js-static-analysis' => 0,
                    'link-integrity' => 1,
                ],
                'expectedTestTypes' => [
                    'CSS validation',
                    'Link integrity',
                ],
            ],
            'html-validation: 0, css-validation: 1, js-static-analysis: 1, link-integrity: 0' => [
                'requestData' =>  [
                    'html-validation' => 0,
                    'css-validation' => 1,
                    'js-static-analysis' => 1,
                    'link-integrity' => 0,
                ],
                'expectedTestTypes' => [
                    'CSS validation',
                    'JS static analysis',
                ],
            ],
            'html-validation: 0, css-validation: 1, js-static-analysis: 1, link-integrity: 1' => [
                'requestData' =>  [
                    'html-validation' => 0,
                    'css-validation' => 1,
                    'js-static-analysis' => 1,
                    'link-integrity' => 1,
                ],
                'expectedTestTypes' => [
                    'CSS validation',
                    'JS static analysis',
                    'Link integrity',
                ],
            ],
            'html-validation: 1, css-validation: 0, js-static-analysis: 0, link-integrity: 0' => [
                'requestData' =>  [
                    'html-validation' => 1,
                    'css-validation' => 0,
                    'js-static-analysis' => 0,
                    'link-integrity' => 0,
                ],
                'expectedTestTypes' => [
                    'HTML validation',
                ],
            ],
            'html-validation: 1, css-validation: 0, js-static-analysis: 0, link-integrity: 1' => [
                'requestData' =>  [
                    'html-validation' => 1,
                    'css-validation' => 0,
                    'js-static-analysis' => 0,
                    'link-integrity' => 1,
                ],
                'expectedTestTypes' => [
                    'HTML validation',
                    'Link integrity',
                ],
            ],
            'html-validation: 1, css-validation: 0, js-static-analysis: 1, link-integrity: 0' => [
                'requestData' =>  [
                    'html-validation' => 1,
                    'css-validation' => 0,
                    'js-static-analysis' => 1,
                    'link-integrity' => 0,
                ],
                'expectedTestTypes' => [
                    'HTML validation',
                    'JS static analysis',
                ],
            ],
            'html-validation: 1, css-validation: 0, js-static-analysis: 1, link-integrity: 1' => [
                'requestData' =>  [
                    'html-validation' => 1,
                    'css-validation' => 0,
                    'js-static-analysis' => 1,
                    'link-integrity' => 1,
                ],
                'expectedTestTypes' => [
                    'HTML validation',
                    'JS static analysis',
                    'Link integrity',
                ],
            ],
            'html-validation: 1, css-validation: 1, js-static-analysis: 0, link-integrity: 0' => [
                'requestData' =>  [
                    'html-validation' => 1,
                    'css-validation' => 1,
                    'js-static-analysis' => 0,
                    'link-integrity' => 0,
                ],
                'expectedTestTypes' => [
                    'HTML validation',
                    'CSS validation',
                ],
            ],
            'html-validation: 1, css-validation: 1, js-static-analysis: 0, link-integrity: 1' => [
                'requestData' =>  [
                    'html-validation' => 1,
                    'css-validation' => 1,
                    'js-static-analysis' => 0,
                    'link-integrity' => 1,
                ],
                'expectedTestTypes' => [
                    'HTML validation',
                    'CSS validation',
                    'Link integrity',
                ],
            ],
            'html-validation: 1, css-validation: 1, js-static-analysis: 1, link-integrity: 0' => [
                'requestData' =>  [
                    'html-validation' => 1,
                    'css-validation' => 1,
                    'js-static-analysis' => 1,
                    'link-integrity' => 0,
                ],
                'expectedTestTypes' => [
                    'HTML validation',
                    'CSS validation',
                    'JS static analysis',
                ],
            ],
            'html-validation: 1, css-validation: 1, js-static-analysis: 1, link-integrity: 1' => [
                'requestData' =>  [
                    'html-validation' => 1,
                    'css-validation' => 1,
                    'js-static-analysis' => 1,
                    'link-integrity' => 1,
                ],
                'expectedTestTypes' => [
                    'HTML validation',
                    'CSS validation',
                    'JS static analysis',
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

        foreach ($features as $feature) {
            $expectedFeatureOptions = $expectedFeatureOptionsCollection[$feature];

            $featureOptions = $testOptions->getFeatureOptions($feature);

            $this->assertEquals($expectedFeatureOptions, $featureOptions);
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

        foreach ($expectedTestTypeOptionsCollection as $testTypeKey => $expectedTestTypeOptions) {
            $testTypeOptions = $testOptions->getTestTypeOptions($testTypeKey);

            $this->assertEquals($expectedTestTypeOptions, $testTypeOptions);
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
                    'js-static-analysis-domains-to-ignore' => 'one.example.com',
                ],
                'expectedTestTypeOptionsCollection' => [
                    'html-validation' => [],
                    'css-validation' => [
                        'css-validation-ignore-warnings' => 1,
                        'css-validation-ignore-common-cdns' => 0,
                        'css-validation-vendor-extensions' => 'warn',
                        'css-validation-domains-to-ignore' => [],
                    ],
                    'js-static-analysis' => [
                        'js-static-analysis-domains-to-ignore' => [
                            'one.example.com',
                        ],
                    ],
                    'link-integrity' => [],
                ],
            ],
        ];
    }

    /**
     * @dataProvider getTestOptionsInvertInvertableOptionsDataProvider
     *
     * @param array $requestData
     * @param array $expectedTestTypeOptionsCollection
     */
    public function testGetTestOptionsInvertInvertableOptions(
        array $requestData,
        array $expectedTestTypeOptionsCollection
    ) {
        $this->requestAdapter->setRequestData(new ParameterBag($requestData));
        $this->requestAdapter->setInvertInvertableOptions(true);

        $testOptions = $this->requestAdapter->getTestOptions();

        foreach ($expectedTestTypeOptionsCollection as $testTypeKey => $expectedTestTypeOptions) {
            $testTypeOptions = $testOptions->getTestTypeOptions($testTypeKey);

            $this->assertEquals($expectedTestTypeOptions, $testTypeOptions);
        }
    }

    /**
     * @return array
     */
    public function getTestOptionsInvertInvertableOptionsDataProvider()
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
                    'js-static-analysis' => [
                        'js-static-analysis-jslint-option-bitwise' => 1,
                        'js-static-analysis-jslint-option-continue' => 1,
                        'js-static-analysis-jslint-option-debug' => 1,
                        'js-static-analysis-jslint-option-evil' => 1,
                        'js-static-analysis-jslint-option-eqeq' => 1,
                        'js-static-analysis-jslint-option-forin' => 1,
                        'js-static-analysis-jslint-option-newcap' => 1,
                        'js-static-analysis-jslint-option-nomen' => 1,
                        'js-static-analysis-jslint-option-plusplus' => 1,
                        'js-static-analysis-jslint-option-regexp' => 1,
                        'js-static-analysis-jslint-option-unparam' => 1,
                        'js-static-analysis-jslint-option-sloppy' => 1,
                        'js-static-analysis-jslint-option-stupid' => 1,
                        'js-static-analysis-jslint-option-sub' => 1,
                        'js-static-analysis-jslint-option-vars' => 1,
                        'js-static-analysis-jslint-option-white' => 1,
                        'js-static-analysis-jslint-option-anon' => 1,
                    ],
                    'link-integrity' => [],
                ],
            ],
            'has test type options' => [
                'requestData' =>  [
                    'html-validation' => 1,
                    'css-validation' => 1,
                    'js-static-analysis' => 1,
                    'link-integrity' => 1,
                    'js-static-analysis-jslint-option-bitwise' => 1,
                    'js-static-analysis-jslint-option-continue' => 1,
                    'js-static-analysis-jslint-option-debug' => 1,
                    'js-static-analysis-jslint-option-evil' => 1,
                    'js-static-analysis-jslint-option-eqeq' => 1,
                    'js-static-analysis-jslint-option-forin' => 1,
                    'js-static-analysis-jslint-option-newcap' => 1,
                    'js-static-analysis-jslint-option-nomen' => 1,
                    'js-static-analysis-jslint-option-plusplus' => 1,
                    'js-static-analysis-jslint-option-regexp' => 1,
                    'js-static-analysis-jslint-option-unparam' => 1,
                    'js-static-analysis-jslint-option-sloppy' => 1,
                    'js-static-analysis-jslint-option-stupid' => 1,
                    'js-static-analysis-jslint-option-sub' => 1,
                    'js-static-analysis-jslint-option-vars' => 1,
                    'js-static-analysis-jslint-option-white' => 1,
                    'js-static-analysis-jslint-option-anon' => 1,
                ],
                'expectedTestTypeOptionsCollection' => [
                    'html-validation' => [],
                    'css-validation' => [],
                    'js-static-analysis' => [
                        'js-static-analysis-jslint-option-bitwise' => 0,
                        'js-static-analysis-jslint-option-continue' => 0,
                        'js-static-analysis-jslint-option-debug' => 0,
                        'js-static-analysis-jslint-option-evil' => 0,
                        'js-static-analysis-jslint-option-eqeq' => 0,
                        'js-static-analysis-jslint-option-forin' => 0,
                        'js-static-analysis-jslint-option-newcap' => 0,
                        'js-static-analysis-jslint-option-nomen' => 0,
                        'js-static-analysis-jslint-option-plusplus' => 0,
                        'js-static-analysis-jslint-option-regexp' => 0,
                        'js-static-analysis-jslint-option-unparam' => 0,
                        'js-static-analysis-jslint-option-sloppy' => 0,
                        'js-static-analysis-jslint-option-stupid' => 0,
                        'js-static-analysis-jslint-option-sub' => 0,
                        'js-static-analysis-jslint-option-vars' => 0,
                        'js-static-analysis-jslint-option-white' => 0,
                        'js-static-analysis-jslint-option-anon' => 0,
                    ],
                    'link-integrity' => [],
                ],
            ],
        ];
    }
}
