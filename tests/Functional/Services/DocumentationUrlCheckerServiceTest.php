<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Services;

use App\Services\DocumentationUrlCheckerService;
use App\Tests\Functional\AbstractBaseTestCase;

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
     */
    public function testExists(string $url, bool $expectedExists)
    {
        $exists = $this->documentationUrlCheckerService->exists($url);

        $this->assertEquals($expectedExists, $exists);
    }

    public function existsDataProvider(): array
    {
        return [
            'foo does not exist' => [
                'url' => 'foo',
                'expectedExists' => false,
            ],
            'does exist' => [
                'url' => 'https://help.simplytestable.com/errors/html-validation/index/',
                'expectedExists' => true,
            ],
        ];
    }
}
