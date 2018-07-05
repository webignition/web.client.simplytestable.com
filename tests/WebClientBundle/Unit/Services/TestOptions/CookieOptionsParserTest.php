<?php

namespace Tests\WebClientBundle\Unit\Services\TestOptions;

use SimplyTestable\WebClientBundle\Services\TestOptions\CookieOptionsParser;
use Symfony\Component\HttpFoundation\ParameterBag;

class CookieOptionsParserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CookieOptionsParser
     */
    private $cookieOptionsParser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->cookieOptionsParser = new CookieOptionsParser();
    }

    /**
     * @dataProvider getOptionsDataProvider
     *
     * @param ParameterBag $requestData
     * @param array $expectedOptions
     */
    public function testGetOptions(ParameterBag $requestData, array $expectedOptions)
    {
        $this->cookieOptionsParser->setFormKey('cookies');
        $this->cookieOptionsParser->setRequestData($requestData);
        $this->cookieOptionsParser->setNamesAndDefaultValues([
            'cookies' => [
                'name' => '',
                'value' => '',
                'secure' => false,
                'path' => '/',
                'domain' => '',
            ],
        ]);

        $options = $this->cookieOptionsParser->getOptions();

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
                'expectedOptions' => [],
            ],
            'empty cookies collection' => [
                'requestData' => new ParameterBag([
                    'cookies' => [],
                ]),
                'expectedOptions' => [
                    'cookies' => [],
                ],
            ],
            'with single cookie' => [
                'requestData' => new ParameterBag([
                    'cookies' => [
                        [
                            'name' => 'cookie-name',
                            'value' => 'cookie-value',
                        ],
                    ],
                ]),
                'expectedOptions' => [
                    'cookies' => [
                        [
                            'name' => 'cookie-name',
                            'value' => 'cookie-value',
                        ],
                    ],
                ],
            ],
            'with multiple cookies' => [
                'requestData' => new ParameterBag([
                    'cookies' => [
                        [
                            'name' => 'cookie-name1',
                            'value' => 'cookie-value1',
                        ],
                        [
                            'name' => 'cookie-name2',
                            'value' => 'cookie-value2',
                        ],
                        [
                            'name' => '',
                            'value' => '',
                        ],
                        [
                            'name' => null,
                            'value' => null,
                        ],
                    ],
                ]),
                'expectedOptions' => [
                    'cookies' => [
                        [
                            'name' => 'cookie-name1',
                            'value' => 'cookie-value1',
                        ],
                        [
                            'name' => 'cookie-name2',
                            'value' => 'cookie-value2',
                        ],
                    ],
                ],
            ],
        ];
    }
}
