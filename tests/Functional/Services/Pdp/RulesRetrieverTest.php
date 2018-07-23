<?php

namespace App\Tests\Functional\Services\Pdp;

use GuzzleHttp\Psr7\Response;
use phpmock\mockery\PHPMockery;
use App\Services\Pdp\RulesRetriever;
use App\Tests\Factory\FixtureLoader;
use App\Tests\Functional\AbstractBaseTestCase;
use App\Tests\Services\HttpMockHandler;

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

        $this->rulesRetriever = self::$container->get(RulesRetriever::class);
    }

    public function testRetrieveHttpException()
    {
        $httpMockHandler = self::$container->get(HttpMockHandler::class);
        $httpMockHandler->appendFixtures([
            new Response(404),
        ]);

        $this->assertFalse($this->rulesRetriever->retrieve());
    }

    public function testRetrieveSuccess()
    {
        $expectedFilePutContentsArg0 = self::$container->getParameter('pdp_psl_data_path');

        $mockedNamespace = 'App\Services\Pdp';

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

        $httpMockHandler = self::$container->get(HttpMockHandler::class);
        $httpMockHandler->appendFixtures([
            new Response(200, ['content-type' => 'text/plain'], FixtureLoader::load('/public_suffix_list.dat')),
        ]);

        $this->assertSame(100, $this->rulesRetriever->retrieve());
    }

    /**
     * {@inheritdoc}
     */
    protected function tearDown()
    {
        parent::tearDown();

        \Mockery::close();
    }
}
