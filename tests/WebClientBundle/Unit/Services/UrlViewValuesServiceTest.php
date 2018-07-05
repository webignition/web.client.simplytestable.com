<?php

namespace Tests\WebClientBundle\Unit\Services;

use SimplyTestable\WebClientBundle\Services\UrlViewValuesService;

class UrlViewValuesServiceTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var UrlViewValuesService
     */
    private $urlViewValuesService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->urlViewValuesService = new UrlViewValuesService();
    }

    /**
     * @dataProvider createDataProvider
     *
     * @param string $url
     * @param string[] $expectedViewValues
     */
    public function testCreate($url, $expectedViewValues)
    {
        $this->assertEquals($expectedViewValues, $this->urlViewValuesService->create($url));
    }

    /**
     * @return array
     */
    public function createDataProvider()
    {
        return [
            'empty' => [
                'url' => '',
                'expectedViewValues' => [],
            ],
            'non-http url' => [
                'url' => 'ftp://example.com/',
                'expectedViewValues' => [
                    'raw' => 'ftp://example.com/',
                    'utf8' => [
                        'raw' => 'ftp://example.com/',
                        'truncated_40' => 'ftp://example.com/',
                        'truncated_50' => 'ftp://example.com/',
                        'truncated_64' => 'ftp://example.com/',
                        'is_truncated_40' => false,
                        'is_truncated_50' => false,
                        'is_truncated_64' => false,
                        'schemeless' => [
                            'raw' => 'ftp://example.com',
                            'truncated_40' => 'ftp://example.com',
                            'truncated_50' => 'ftp://example.com',
                            'truncated_64' => 'ftp://example.com',
                            'is_truncated_40' => false,
                            'is_truncated_50' => false,
                            'is_truncated_64' => false,
                        ],
                    ],
                ],
            ],
            'short url; http' => [
                'url' => 'http://example.com/',
                'expectedViewValues' => [
                    'raw' => 'http://example.com/',
                    'utf8' => [
                        'raw' => 'http://example.com/',
                        'truncated_40' => 'http://example.com/',
                        'truncated_50' => 'http://example.com/',
                        'truncated_64' => 'http://example.com/',
                        'is_truncated_40' => false,
                        'is_truncated_50' => false,
                        'is_truncated_64' => false,
                        'schemeless' => [
                            'raw' => 'example.com',
                            'truncated_40' => 'example.com',
                            'truncated_50' => 'example.com',
                            'truncated_64' => 'example.com',
                            'is_truncated_40' => false,
                            'is_truncated_50' => false,
                            'is_truncated_64' => false,
                        ],
                    ],
                ],
            ],
            'short url; https' => [
                'url' => 'https://example.com/',
                'expectedViewValues' => [
                    'raw' => 'https://example.com/',
                    'utf8' => [
                        'raw' => 'https://example.com/',
                        'truncated_40' => 'https://example.com/',
                        'truncated_50' => 'https://example.com/',
                        'truncated_64' => 'https://example.com/',
                        'is_truncated_40' => false,
                        'is_truncated_50' => false,
                        'is_truncated_64' => false,
                        'schemeless' => [
                            'raw' => 'example.com',
                            'truncated_40' => 'example.com',
                            'truncated_50' => 'example.com',
                            'truncated_64' => 'example.com',
                            'is_truncated_40' => false,
                            'is_truncated_50' => false,
                            'is_truncated_64' => false,
                        ],
                    ],
                ],
            ],
            'long url; 41 characters' => [
                'url' => 'http://example.com/0123456789012345678901',
                'expectedViewValues' => [
                    'raw' => 'http://example.com/0123456789012345678901',
                    'utf8' => [
                        'raw' => 'http://example.com/0123456789012345678901',
                        'truncated_40' => 'http://example.com/012345678901234567890',
                        'truncated_50' => 'http://example.com/0123456789012345678901',
                        'truncated_64' => 'http://example.com/0123456789012345678901',
                        'is_truncated_40' => true,
                        'is_truncated_50' => false,
                        'is_truncated_64' => false,
                        'schemeless' => [
                            'raw' => 'example.com/0123456789012345678901',
                            'truncated_40' => 'example.com/0123456789012345678901',
                            'truncated_50' => 'example.com/0123456789012345678901',
                            'truncated_64' => 'example.com/0123456789012345678901',
                            'is_truncated_40' => false,
                            'is_truncated_50' => false,
                            'is_truncated_64' => false,
                        ],
                    ],
                ],
            ],
            'long url; 51 characters' => [
                'url' => 'http://example.com/01234567890123456789012345678901',
                'expectedViewValues' => [
                    'raw' => 'http://example.com/01234567890123456789012345678901',
                    'utf8' => [
                        'raw' => 'http://example.com/01234567890123456789012345678901',
                        'truncated_40' => 'http://example.com/012345678901234567890',
                        'truncated_50' => 'http://example.com/0123456789012345678901234567890',
                        'truncated_64' => 'http://example.com/01234567890123456789012345678901',
                        'is_truncated_40' => true,
                        'is_truncated_50' => true,
                        'is_truncated_64' => false,
                        'schemeless' => [
                            'raw' => 'example.com/01234567890123456789012345678901',
                            'truncated_40' => 'example.com/0123456789012345678901234567',
                            'truncated_50' => 'example.com/01234567890123456789012345678901',
                            'truncated_64' => 'example.com/01234567890123456789012345678901',
                            'is_truncated_40' => true,
                            'is_truncated_50' => false,
                            'is_truncated_64' => false,
                        ],
                    ],
                ],
            ],
            'long url; 65 characters' => [
                'url' => 'http://example.com/0123456789012345678901234567890123456789012345',
                'expectedViewValues' => [
                    'raw' => 'http://example.com/0123456789012345678901234567890123456789012345',
                    'utf8' => [
                        'raw' => 'http://example.com/0123456789012345678901234567890123456789012345',
                        'truncated_40' => 'http://example.com/012345678901234567890',
                        'truncated_50' => 'http://example.com/0123456789012345678901234567890',
                        'truncated_64' => 'http://example.com/012345678901234567890123456789012345678901234',
                        'is_truncated_40' => true,
                        'is_truncated_50' => true,
                        'is_truncated_64' => true,
                        'schemeless' => [
                            'raw' => 'example.com/0123456789012345678901234567890123456789012345',
                            'truncated_40' => 'example.com/0123456789012345678901234567',
                            'truncated_50' => 'example.com/01234567890123456789012345678901234567',
                            'truncated_64' => 'example.com/0123456789012345678901234567890123456789012345',
                            'is_truncated_40' => true,
                            'is_truncated_50' => true,
                            'is_truncated_64' => false,
                        ],
                    ],
                ],
            ],
            'long url; 72 characters' => [
                'url' => 'http://example.com/01234567890123456789012345678901234567890123456789012',
                'expectedViewValues' => [
                    'raw' => 'http://example.com/01234567890123456789012345678901234567890123456789012',
                    'utf8' => [
                        'raw' => 'http://example.com/01234567890123456789012345678901234567890123456789012',
                        'truncated_40' => 'http://example.com/012345678901234567890',
                        'truncated_50' => 'http://example.com/0123456789012345678901234567890',
                        'truncated_64' => 'http://example.com/012345678901234567890123456789012345678901234',
                        'is_truncated_40' => true,
                        'is_truncated_50' => true,
                        'is_truncated_64' => true,
                        'schemeless' => [
                            'raw' => 'example.com/01234567890123456789012345678901234567890123456789012',
                            'truncated_40' => 'example.com/0123456789012345678901234567',
                            'truncated_50' => 'example.com/01234567890123456789012345678901234567',
                            'truncated_64' => 'example.com/0123456789012345678901234567890123456789012345678901',
                            'is_truncated_40' => true,
                            'is_truncated_50' => true,
                            'is_truncated_64' => true,
                        ],
                    ],
                ],
            ],
        ];
    }
}
