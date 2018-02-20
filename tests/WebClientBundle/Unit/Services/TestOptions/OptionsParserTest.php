<?php

namespace Tests\WebClientBundle\Unit\Services\TestOptions;

use SimplyTestable\WebClientBundle\Services\TestOptions\OptionsParser;
use Symfony\Component\HttpFoundation\ParameterBag;

class OptionsParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var OptionsParser
     */
    private $optionsParser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->optionsParser = new OptionsParser();
    }

    /**
     * @dataProvider getOptionsDataProvider
     *
     * @param ParameterBag $requestData
     * @param string $formKey
     * @param array $expectedOptions
     */
    public function testGetOptions(ParameterBag $requestData, $formKey, array $expectedOptions)
    {
        $this->optionsParser->setFormKey($formKey);
        $this->optionsParser->setRequestData($requestData);
        $this->optionsParser->setNamesAndDefaultValues([
            'http-auth-username' => '',
            'http-auth-password' => '',
            'html-validation' => 1,
            'cookies' => [
                'name' => '',
                'value' => '',
                'secure' => false,
                'path' => '/',
                'domain' => '',
            ],
            'css-validation-domains-to-ignore' => [
                '',
            ],
            'js-static-analysis-domains-to-ignore' => [
                '',
            ],
        ]);

        $options = $this->optionsParser->getOptions();

        $this->assertEquals($expectedOptions, $options);
    }

    /**
     * @return array
     */
    public function getOptionsDataProvider()
    {
        return [
            'empty request' => [
                'requestData' => new ParameterBag(),
                'formKey' => '',
                'expectedOptions' => [],
            ],
            'non-empty request' => [
                'requestData' => new ParameterBag([
                    'http-auth-username' => 'foo',
                    'http-auth-password' => 'bar',
                    'html-validation' => 0,
                    'cookies' => [
                        'name' => '',
                        'value' => '',
                    ],
                    'css-validation-domains-to-ignore' => [
                        'css-foo.com',
                        'css-bar.com',
                        1,
                        [
                            'a', null, '',
                        ]
                    ],
                    'js-static-analysis-domains-to-ignore' => "js-foo.com\njs-bar.com"
                ]),
                'formKey' => '',
                'expectedOptions' => [
                    'http-auth-username' => 'foo',
                    'http-auth-password' => 'bar',
                    'html-validation' => 0,
                    'cookies' => [
                        'name' => null,
                        'value' => null,
                    ],
                    'css-validation-domains-to-ignore' => [
                        'css-foo.com',
                        'css-bar.com',
                        1,
                        [
                            'a', null, null,
                        ]
                    ],
                    'js-static-analysis-domains-to-ignore' => [
                        'js-foo.com',
                        'js-bar.com',
                    ],
                ],
            ],
        ];
    }
}
