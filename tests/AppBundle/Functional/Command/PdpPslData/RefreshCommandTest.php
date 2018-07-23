<?php

namespace Tests\AppBundle\Functional\Command\PdpPslData;

use Mockery\MockInterface;
use App\Command\PdpPslData\RefreshCommand;
use App\Services\Pdp\RulesRetriever;
use Tests\AppBundle\Functional\AbstractBaseTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class RefreshCommandTest extends AbstractBaseTestCase
{
    /**
     * @dataProvider runDataProvider
     *
     * @param RulesRetriever $rulesRetriever
     * @param int $expectedReturnCode
     */
    public function testRun(RulesRetriever $rulesRetriever, $expectedReturnCode)
    {
        $refreshCommand = new RefreshCommand($rulesRetriever);

        $returnCode = $refreshCommand->run(new ArrayInput([]), new NullOutput());

        $this->assertSame($expectedReturnCode, $returnCode);
    }

    /**
     * @return array
     */
    public function runDataProvider()
    {
        return [
            'failed' => [
                'rulesRetriever' => $this->createRulesRetriever(false),
                'expectedReturnCode' => RefreshCommand::RETURN_CODE_FAILURE,
            ],
            'success' => [
                'rulesRetriever' => $this->createRulesRetriever(100),
                'expectedReturnCode' => RefreshCommand::RETURN_CODE_OK,
            ],
        ];
    }

    /**
     * @param int|bool $retrievalResult
     *
     * @return MockInterface|RulesRetriever
     */
    private function createRulesRetriever($retrievalResult)
    {
        /* @var MockInterface|RulesRetriever $retriever */
        $retriever = \Mockery::mock(RulesRetriever::class);

        $retriever
            ->shouldReceive('retrieve')
            ->andReturn($retrievalResult);

        return $retriever;
    }
}
