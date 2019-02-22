<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Model;

use App\Model\TestOptions;

class TestOptionsTest extends \PHPUnit\Framework\TestCase
{
    private $setFeatureOptionsCalls = [
        [
            'name' => 'http-authentication',
            'options' => [
                'http-auth-username' => 'foo',
                'http-auth-password' => 'bar',
            ],
        ],
        [
            'name' => 'cookies',
            'options' => [
                'cookies' => [],
            ],
        ],
        [
            'name' => 'foo',
            'options' => [
                'foo-name-1' => 'foo-value-1',
                'foo-name-2' => 'foo-value-2',
            ],
        ],
        [
            'name' => 'invalid',
            'options' => true,
        ],
    ];

    private $addTestTypeOptionsCalls = [
        [
            'name' => 'html-validation',
            'options' => [],
        ],
        [
            'name' => 'css-validation',
            'options' => [
                'css-validation-ignore-warnings' => 1,
                'css-validation-ignore-common-cdns' => 0,
                'css-validation-vendor-extensions' => 'warn',
                'css-validation-domains-to-ignore' => [],
            ],
        ],
        [
            'name' => 'invalid',
            'options' => true,
        ],
    ];

    private $availableTaskTypes = [
        'html-validation' => [
            'name' => 'HTML validation',
        ],
        'css-validation' => [
            'name' => 'CSS validation',
        ],
        'link-integrity' => [
            'name' => 'Link integrity',
        ],
    ];

    private $availableFeatures = [
        'http-authentication' => [
            'enabled' => true,
            'form_key' => 'http-auth',
        ],
        'cookies' => [
            'enabled' => true,
            'form_key' => 'cookies',
        ],
    ];

    /**
     * @var TestOptions
     */
    private $testOptions;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->testOptions = new TestOptions();
    }

    /**
     * @dataProvider addRemoveHasGetTestTypesDataProvider
     */
    public function testAddRemoveHasGetTestTypes(
        array $testTypesToAdd,
        array $testTypesToRemove,
        bool $expectedHasTestTypes,
        array $expectedTestTypes
    ) {
        $this->buildTestTypes($testTypesToAdd);

        foreach ($testTypesToRemove as $testTypeKey) {
            $this->testOptions->removeTestType($testTypeKey);
        }

        $this->assertEquals($expectedHasTestTypes, $this->testOptions->hasTestTypes());
        $this->assertEquals($expectedTestTypes, $this->testOptions->getTestTypes());
    }

    public function addRemoveHasGetTestTypesDataProvider(): array
    {
        return [
            'none' => [
                'testTypesToAdd' => [],
                'testTypesToRemove' => [],
                'expectedHasTestTypes' => false,
                'expectedTestTypes' => [],
            ],
            'add one' => [
                'testTypesToAdd' => [
                    'foo' => 'Foo Name',
                ],
                'testTypesToRemove' => [],
                'expectedHasTestTypes' => true,
                'expectedTestTypes' => [
                    'Foo Name',
                ],
            ],
            'remove only' => [
                'testTypesToAdd' => [],
                'testTypesToRemove' => [
                    'foo',
                ],
                'expectedHasTestTypes' => false,
                'expectedTestTypes' => [],
            ],
            'add two' => [
                'testTypesToAdd' => [
                    'foo' => 'Foo Name',
                    'bar' => 'Bar Name',
                ],
                'testTypesToRemove' => [],
                'expectedHasTestTypes' => true,
                'expectedTestTypes' => [
                    'Foo Name',
                    'Bar Name',
                ],
            ],
            'add two, remove one' => [
                'testTypesToAdd' => [
                    'foo' => 'Foo Name',
                    'bar' => 'Bar Name',
                ],
                'testTypesToRemove' => [
                    'foo',
                ],
                'expectedHasTestTypes' => true,
                'expectedTestTypes' => [
                    'Bar Name',
                ],
            ],
            'add two, remove two' => [
                'testTypesToAdd' => [
                    'foo' => 'Foo Name',
                    'bar' => 'Bar Name',
                ],
                'testTypesToRemove' => [
                    'foo',
                    'bar',
                ],
                'expectedHasTestTypes' => false,
                'expectedTestTypes' => [],
            ],
        ];
    }

    /**
     * @dataProvider setRemoveGetFeatureOptionsDataProvider
     */
    public function testSetRemoveGetFeatureOptions(
        array $removeFeatureOptionsCalls,
        string $getFeatureOptionsFeature,
        array $expectedFeatureOptions
    ) {
        $this->buildFeatureOptions($this->setFeatureOptionsCalls);

        foreach ($removeFeatureOptionsCalls as $featureKey) {
            $this->testOptions->removeFeatureOptions($featureKey);
        }

        $featureOptions = $this->testOptions->getFeatureOptions($getFeatureOptionsFeature);

        $this->assertEquals($expectedFeatureOptions, $featureOptions);
    }

    public function setRemoveGetFeatureOptionsDataProvider(): array
    {
        return [
            'none' => [
                'removeFeatureOptionsCalls' => [],
                'getFeatureOptionsFeature' => 'bar',
                'expectedFeatureOptions' => [],
            ],
            'set, get' => [
                'removeFeatureOptionsCalls' => [],
                'getFeatureOptionsFeature' => 'http-authentication',
                'expectedFeatureOptions' => [
                    'http-auth-username' => 'foo',
                    'http-auth-password' => 'bar',
                ],
            ],
            'set many, remove some, get' => [
                'removeFeatureOptionsCalls' => [],
                'getFeatureOptionsFeature' => 'foo',
                'expectedFeatureOptions' => [
                    'foo-name-1' => 'foo-value-1',
                    'foo-name-2' => 'foo-value-2',
                ],
            ],
            'set, remove, get' => [
                'removeFeatureOptionsCalls' => [
                    'foo',
                ],
                'getFeatureOptionsFeature' => 'foo',
                'expectedFeatureOptions' => [],
            ],
        ];
    }

    /**
     * @dataProvider hasFeatureOptionsDataProvider
     */
    public function testHasFeatureOptions(string $featureKey, bool $expectedHasFeatureOptions)
    {
        $this->buildFeatureOptions($this->setFeatureOptionsCalls);

        $hasFeatureOptions = $this->testOptions->hasFeatureOptions($featureKey);

        $this->assertEquals($expectedHasFeatureOptions, $hasFeatureOptions);
    }

    public function hasFeatureOptionsDataProvider(): array
    {
        return [
            'has http-authentication' => [
                'featureKey' => 'http-authentication',
                'expectedHasFeatureOptions' => true,
            ],
            'has cookies' => [
                'featureKey' => 'cookies',
                'expectedHasFeatureOptions' => true,
            ],
            'has foo' => [
                'featureKey' => 'foo',
                'expectedHasFeatureOptions' => true,
            ],
            'has not bar' => [
                'featureKey' => 'bar',
                'expectedHasFeatureOptions' => false,
            ],
            'has not invalid' => [
                'featureKey' => 'invalid',
                'expectedHasFeatureOptions' => false,
            ],
        ];
    }

    public function testHasFeatures()
    {
        $this->assertFalse($this->testOptions->hasFeatures());

        $this->buildFeatureOptions($this->setFeatureOptionsCalls);

        $this->assertTrue($this->testOptions->hasFeatures());
    }

    public function testGetFeatures()
    {
        $this->buildFeatureOptions($this->setFeatureOptionsCalls);

        $this->assertEquals(
            [
                'http-authentication',
                'cookies',
                'foo',
                'invalid',
            ],
            $this->testOptions->getFeatures()
        );
    }

    /**
     * @dataProvider getTestTypeOptionsDataProvider
     */
    public function testGetTestTypeOptions(string $testTypeName, array $expectedTestTypeOptions)
    {
        $this->buildTestTypeOptions($this->addTestTypeOptionsCalls);

        $testTypeOptions = $this->testOptions->getTestTypeOptions($testTypeName);

        $this->assertEquals($expectedTestTypeOptions, $testTypeOptions);
    }

    public function getTestTypeOptionsDataProvider(): array
    {
        return [
            'html-validation' => [
                'testTypeName' => 'html-validation',
                'expectedTestTypeOptions' => [],
            ],
            'css-validation' => [
                'testTypeName' => 'css-validation',
                'expectedTestTypeOptions' => [
                    'css-validation-ignore-warnings' => 1,
                    'css-validation-ignore-common-cdns' => 0,
                    'css-validation-vendor-extensions' => 'warn',
                    'css-validation-domains-to-ignore' => [],
                ],
            ],
            'invalid test type' => [
                'testTypeName' => 'foo',
                'expectedTestTypeOptions' => [],
            ],
        ];
    }

    /**
     * @dataProvider hasTestTypeOptionsDataProvider
     */
    public function testHasTestTypeOptions(string $testTypeName, bool $expectedHasTestTypeOptions)
    {
        $this->buildTestTypeOptions($this->addTestTypeOptionsCalls);

        $hasTestTypeOptions = $this->testOptions->hasTestTypeOptions($testTypeName);

        $this->assertEquals($expectedHasTestTypeOptions, $hasTestTypeOptions);
    }

    public function hasTestTypeOptionsDataProvider(): array
    {
        return [
            'has css-validation' => [
                'testTypeName' => 'css-validation',
                'expectedHasTestTypeOptions' => true,
            ],
            'has not html-validation; is empty' => [
                'testTypeName' => 'html-validation',
                'expectedHasTestTypeOptions' => false,
            ],
            'has not foo; not set' => [
                'testTypeName' => 'foo',
                'expectedHasTestTypeOptions' => false,
            ],
            'has not invalid; is set but is invalid' => [
                'testTypeName' => 'invalid',
                'expectedTestTypeOptions' => false,
            ],
        ];
    }

    /**
     * @dataProvider getAbsoluteTestTypeOptionsDataProvider
     */
    public function testGetAbsoluteTestTypeOptions(
        string $testType,
        bool $useFullOptionKey,
        array $expectedAbsoluteTestTypeOptions
    ) {
        $this->buildTestTypeOptions($this->addTestTypeOptionsCalls);

        $absoluteTestTypeOptions = $this->testOptions->getAbsoluteTestTypeOptions($testType, $useFullOptionKey);

        $this->assertEquals($expectedAbsoluteTestTypeOptions, $absoluteTestTypeOptions);
    }

    public function getAbsoluteTestTypeOptionsDataProvider(): array
    {
        return [
            'html-validation; useFullOptionKey=true' => [
                'testType' => 'html-validation',
                'useFullOptionKey' => true,
                'expectedAbsoluteTestTypeOptions' => [],
            ],
            'html-validation; useFullOptionKey=false' => [
                'testType' => 'html-validation',
                'useFullOptionKey' => false,
                'expectedAbsoluteTestTypeOptions' => [],
            ],
            'css-validation; useFullOptionKey=true' => [
                'testType' => 'css-validation',
                'useFullOptionKey' => true,
                'expectedAbsoluteTestTypeOptions' => [
                    'css-validation-ignore-warnings' => 1,
                    'css-validation-ignore-common-cdns' => 0,
                    'css-validation-vendor-extensions' => 'warn',
                    'css-validation-domains-to-ignore' => [],
                ],
            ],
            'css-validation; useFullOptionKey=false' => [
                'testType' => 'css-validation',
                'useFullOptionKey' => false,
                'expectedAbsoluteTestTypeOptions' => [
                    'ignore-warnings' => 1,
                    'ignore-common-cdns' => 0,
                    'vendor-extensions' => 'warn',
                    'domains-to-ignore' => [],
                ],
            ],
            'invalid' => [
                'testType' => 'invalid',
                'useFullOptionKey' => true,
                'expectedAbsoluteTestTypeOptions' => [],
            ],
        ];
    }

    /**
     * @dataProvider getAbsoluteFeatureOptionsDataProvider
     */
    public function testGetAbsoluteFeatureOptions(string $featureKey, array $expectedAbsoluteFeatureOptions)
    {
        $this->buildFeatureOptions($this->setFeatureOptionsCalls);

        $absoluteFeatureOptions = $this->testOptions->getAbsoluteFeatureOptions($featureKey);

        $this->assertEquals($expectedAbsoluteFeatureOptions, $absoluteFeatureOptions);
    }

    public function getAbsoluteFeatureOptionsDataProvider(): array
    {
        return [
            'http-authentication' => [
                'featureKey' => 'http-authentication',
                'expectedAbsoluteFeatureOptions' => [
                    'http-auth-username' => 'foo',
                    'http-auth-password' => 'bar',
                ],
            ],
            'cookies' => [
                'featureKey' => 'cookies',
                'expectedAbsoluteFeatureOptions' => [
                    'cookies' => [],
                ],
            ],
            'foo' => [
                'featureKey' => 'foo',
                'expectedAbsoluteFeatureOptions' => [
                    'foo-name-1' => 'foo-value-1',
                    'foo-name-2' => 'foo-value-2',
                ],
            ],
            'invalid' => [
                'featureKey' => 'invalid',
                'expectedAbsoluteFeatureOptions' => [],
            ],
        ];
    }

    /**
     * @dataProvider getAbsoluteTestTypesDataProvider
     */
    public function testGetAbsoluteTestTypes(array $testTypesToAdd, array $expectedAbsoluteTestTypes)
    {
        $this->testOptions->setAvailableTaskTypes($this->availableTaskTypes);
        $this->buildTestTypes($testTypesToAdd);

        $this->assertEquals($expectedAbsoluteTestTypes, $this->testOptions->getAbsoluteTestTypes());
    }

    public function getAbsoluteTestTypesDataProvider(): array
    {
        return [
            'no test types added' => [
                'testTypesToAdd' => [],
                'expectedAbsoluteTestTypes' => [
                    'html-validation' => 0,
                    'css-validation' => 0,
                    'link-integrity' => 0,
                ],
            ],
            'invalid test type added' => [
                'testTypesToAdd' => [
                    'foo' => [
                        'name' => 'Foo Name',
                    ],
                ],
                'expectedAbsoluteTestTypes' => [
                    'html-validation' => 0,
                    'css-validation' => 0,
                    'link-integrity' => 0,
                ],
            ],
            'html-validation only' => [
                'testTypesToAdd' => [
                    'html-validation' => [
                        'name' => 'HTML validation',
                    ],
                ],
                'expectedAbsoluteTestTypes' => [
                    'html-validation' => 1,
                    'css-validation' => 0,
                    'link-integrity' => 0,
                ],
            ],
            'html-validation and css-validation' => [
                'testTypesToAdd' => [
                    'html-validation' => [
                        'name' => 'HTML validation',
                    ],
                    'css-validation' => [
                        'name' => 'CSS validation',
                    ],
                ],
                'expectedAbsoluteTestTypes' => [
                    'html-validation' => 1,
                    'css-validation' => 1,
                    'link-integrity' => 0,
                ],
            ],
        ];
    }

    /**
     * @dataProvider getAbsoluteFeaturesDataProvider
     */
    public function testGetAbsoluteFeatures(array $setFeatureOptionsCalls, array $expectedAbsoluteFeatures)
    {
        $this->testOptions->setAvailableFeatures($this->availableFeatures);
        $this->buildFeatureOptions($setFeatureOptionsCalls);

        $this->assertEquals($expectedAbsoluteFeatures, $this->testOptions->getAbsoluteFeatures());
    }

    public function getAbsoluteFeaturesDataProvider(): array
    {
        return [
            'none' => [
                'setFeatureOptionsCalls' => [],
                'expectedAbsoluteFeatures' => [
                    'http-authentication' => 0,
                    'cookies' => 0,
                ],
            ],
            'http-authentication' => [
                'setFeatureOptionsCalls' => [
                    [
                        'name' => 'http-authentication',
                        'options' => [
                            'http-auth-username' => 'foo',
                            'http-auth-password' => 'bar',
                        ],
                    ],
                ],
                'expectedAbsoluteFeatures' => [
                    'http-authentication' => 1,
                    'cookies' => 0,
                ],
            ],
            'cookies' => [
                'setFeatureOptionsCalls' => [
                    [
                        'name' => 'cookies',
                        'options' => [
                            'cookies' => [],
                        ],
                    ],
                ],
                'expectedAbsoluteFeatures' => [
                    'http-authentication' => 0,
                    'cookies' => 1,
                ],
            ],
        ];
    }

    /**
     * @dataProvider toArrayDataProvider
     */
    public function testToArray(
        array $testTypesToAdd,
        array $addTestTypeOptionsCalls,
        array $setFeatureOptionsCalls,
        array $expectedArray
    ) {
        $this->testOptions->setAvailableTaskTypes($this->availableTaskTypes);

        $this->buildTestTypes($testTypesToAdd);
        $this->buildTestTypeOptions($addTestTypeOptionsCalls);
        $this->buildFeatureOptions($setFeatureOptionsCalls);

        $array = $this->testOptions->__toArray();
        $this->assertEquals($expectedArray, $array);
    }

    public function toArrayDataProvider(): array
    {
        return [
            'none' => [
                'testTypesToAdd' => [],
                'addTestTypeOptionsCalls' => [],
                'setFeatureOptionsCalls' => [],
                'expectedArray' => [],
            ],
            'test types and features' => [
                'testTypesToAdd' => [
                    'html-validation' => [
                        'name' => 'HTML validation',
                    ],
                    'css-validation' => [
                        'name' => 'CSS validation',
                    ],
                ],
                'addTestTypeOptionsCalls' => [
                    [
                        'name' => 'html-validation',
                        'options' => [],
                    ],
                    [
                        'name' => 'css-validation',
                        'options' => [
                            'css-validation-ignore-warnings' => 1,
                        ],
                    ],
                ],
                'setFeatureOptionsCalls' => [
                    [
                        'name' => 'http-authentication',
                        'options' => [
                            'http-auth-username' => 'foo',
                            'http-auth-password' => 'bar',
                        ],
                    ],
                    [
                        'name' => 'cookies',
                        'options' => [
                            'cookies' => [],
                        ],
                    ],
                ],
                'expectedArray' => [
                    'test-types' => [
                        'HTML validation',
                        'CSS validation',
                    ],
                    'test-type-options' => [
                        'HTML validation' => [],
                        'CSS validation' => [
                            'ignore-warnings' => 1,
                        ],
                        'Link integrity' => [],
                    ],
                    'parameters' => [
                        'http-auth-username' => 'foo',
                        'http-auth-password' => 'bar',
                        'cookies' => [],
                    ],
                ],
            ],
        ];
    }

    /**
     * @dataProvider toKeyArrayDataProvider
     */
    public function testToKeyArray(
        array $testTypesToAdd,
        array $addTestTypeOptionsCalls,
        array $setFeatureOptionsCalls,
        array $expectedArray
    ) {
        $this->testOptions->setAvailableTaskTypes($this->availableTaskTypes);

        $this->buildTestTypes($testTypesToAdd);
        $this->buildTestTypeOptions($addTestTypeOptionsCalls);
        $this->buildFeatureOptions($setFeatureOptionsCalls);

        $array = $this->testOptions->__toKeyArray();
        $this->assertEquals($expectedArray, $array);
    }

    public function toKeyArrayDataProvider(): array
    {
        return [
            'none' => [
                'testTypesToAdd' => [],
                'addTestTypeOptionsCalls' => [],
                'setFeatureOptionsCalls' => [],
                'expectedArray' => [],
            ],
            'test types and features' => [
                'testTypesToAdd' => [
                    'html-validation' => [
                        'name' => 'HTML validation',
                    ],
                    'css-validation' => [
                        'name' => 'CSS validation',
                    ],
                ],
                'addTestTypeOptionsCalls' => [
                    [
                        'name' => 'html-validation',
                        'options' => [],
                    ],
                    [
                        'name' => 'css-validation',
                        'options' => [
                            'css-validation-ignore-warnings' => 1,
                        ],
                    ],
                ],
                'setFeatureOptionsCalls' => [
                    [
                        'name' => 'http-authentication',
                        'options' => [
                            'http-auth-username' => 'foo',
                            'http-auth-password' => 'bar',
                        ],
                    ],
                    [
                        'name' => 'cookies',
                        'options' => [
                            'cookies' => [],
                        ],
                    ],
                ],
                'expectedArray' => [
                    'http-authentication' => 1,
                    'cookies' => [],
                    'html-validation' => 1,
                    'css-validation' => 1,
                    'css-validation-ignore-warnings' => 1,
                    'http-auth-username' => 'foo',
                    'http-auth-password' => 'bar',
                ],
            ],
        ];
    }

    private function buildFeatureOptions(array $setFeatureOptionsCalls)
    {
        foreach ($setFeatureOptionsCalls as $setFeatureOptionsCall) {
            $this->testOptions->setFeatureOptions($setFeatureOptionsCall['name'], $setFeatureOptionsCall['options']);
        }
    }

    private function buildTestTypeOptions(array $addTestTypeOptionsCalls)
    {
        foreach ($addTestTypeOptionsCalls as $addTestTypeOptionsCall) {
            $this->testOptions->addTestTypeOptions($addTestTypeOptionsCall['name'], $addTestTypeOptionsCall['options']);
        }
    }

    private function buildTestTypes(array $testTypesToAdd)
    {
        foreach ($testTypesToAdd as $testTypeKey => $testTypeDetails) {
            $this->testOptions->addTestType($testTypeKey, $testTypeDetails);
        }
    }
}
