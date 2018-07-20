<?php

namespace Tests\AppBundle\Functional\Services;

use AppBundle\Services\DocumentationUrlCheckerService;
use Tests\AppBundle\Functional\AbstractBaseTestCase;

class DocumentationUrlCheckerServiceTest extends AbstractBaseTestCase
{
    /**
     * @var DocumentationUrlCheckerService
     */
    private $documentationUrlCheckerService;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->documentationUrlCheckerService = self::$container->get(DocumentationUrlCheckerService::class);
    }

    /**
     * @dataProvider existsDataProvider
     *
     * @param string $url
     * @param bool $expectedExists
     */
    public function testExists($url, $expectedExists)
    {
        $exists = $this->documentationUrlCheckerService->exists($url);

        $this->assertEquals($expectedExists, $exists);
    }

    /**
     * @return array
     */
    public function existsDataProvider()
    {
        return [
            'foo does not exist' => [
                'url' => 'foo',
                'expectedExists' => false,
            ],
            'does exist' => [
                'url' => 'http://help.simplytestable.com/errors/html-validation/index/',
                'expectedExists' => true,
            ],
        ];
    }
}
