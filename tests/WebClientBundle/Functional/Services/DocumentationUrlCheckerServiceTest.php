<?php

namespace Tests\WebClientBundle\Functional\Services;

use SimplyTestable\WebClientBundle\Services\DocumentationUrlCheckerService;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;

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

        $this->documentationUrlCheckerService = new DocumentationUrlCheckerService();

        $this->documentationUrlCheckerService->setDocumentationSitemapPath(
            $this->container->get('kernel')->locateResource(
                '@SimplyTestableWebClientBundle/Resources/config/documentation_sitemap.xml'
            )
        );
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
