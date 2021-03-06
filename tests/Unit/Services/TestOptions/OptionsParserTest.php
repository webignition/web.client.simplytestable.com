<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services\TestOptions;

use App\Services\TestOptions\OptionsParser;
use Symfony\Component\HttpFoundation\ParameterBag;

class OptionsParserTest extends \PHPUnit\Framework\TestCase
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
     */
    public function testGetOptions(ParameterBag $requestData, string $formKey, array $expectedOptions)
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
        ]);

        $options = $this->optionsParser->getOptions();

        $this->assertEquals($expectedOptions, $options);
    }

    public function getOptionsDataProvider(): array
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
                ],
            ],
        ];
    }
}
