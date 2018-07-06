<?php

namespace Tests\WebClientBundle\Functional\Services\Pdp;

use GuzzleHttp\Psr7\Response;
use phpmock\mockery\PHPMockery;
use SimplyTestable\WebClientBundle\Services\Pdp\RulesRetriever;
use Tests\WebClientBundle\Factory\FixtureLoader;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;
use Tests\WebClientBundle\Services\HttpMockHandler;

class RulesRetrieverTest extends AbstractBaseTestCase
{
    /**
     * @var RulesRetriever
     */
    private $rulesRetriever;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->rulesRetriever = $this->container->get(RulesRetriever::class);
    }

    public function testRetrieveHttpException()
    {
        $httpMockHandler = $this->container->get(HttpMockHandler::class);
        $httpMockHandler->appendFixtures([
            new Response(404),
        ]);

        $this->assertFalse($this->rulesRetriever->retrieve());
    }

    public function testRetrieveSuccess()
    {
        $expectedFilePutContentsArg0 = $this->container->getParameter('pdp_psl_data_path');

        $mockedNamespace = 'SimplyTestable\WebClientBundle\Services\Pdp';

        PHPMockery::mock(
            $mockedNamespace,
            'file_put_contents'
        )
            ->withArgs(function ($path, $contents) use ($expectedFilePutContentsArg0) {
                $this->assertSame($expectedFilePutContentsArg0, $path);
                $this->assertInternalType('string', $contents);

                $decodedContents = json_decode($contents, true);

                $this->assertInternalType('array', $decodedContents);

                $this->assertSame(
                    [
                        'ICANN_DOMAINS',
                        'PRIVATE_DOMAINS',
                    ],
                    array_keys($decodedContents)
                );

                return true;
            })
            ->andReturn(100);

        $httpMockHandler = $this->container->get(HttpMockHandler::class);
        $httpMockHandler->appendFixtures([
            new Response(200, ['content-type' => 'text/plain'], FixtureLoader::load('/public_suffix_list.dat')),
        ]);

        $this->assertSame(100, $this->rulesRetriever->retrieve());
    }

    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
