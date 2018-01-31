<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\CacheValidatorHeaders;
use SimplyTestable\WebClientBundle\Services\CacheValidatorHeadersService;
use SimplyTestable\WebClientBundle\Services\DocumentationUrlCheckerService;
use SimplyTestable\WebClientBundle\Tests\Functional\AbstractBaseTestCase;

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

        $this->documentationUrlCheckerService = $this->container->get(
            'simplytestable.services.documentationurlcheckerservice'
        );

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
