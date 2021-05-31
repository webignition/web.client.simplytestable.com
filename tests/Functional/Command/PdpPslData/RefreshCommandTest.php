<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Functional\Command\PdpPslData;

use Mockery\MockInterface;
use App\Command\PdpPslData\RefreshCommand;
use App\Services\Pdp\RulesRetriever;
use App\Tests\Functional\AbstractBaseTestCase;
use Symfony\Component\Console\Input\ArrayInput;
use Symfony\Component\Console\Output\NullOutput;

class RefreshCommandTest extends AbstractBaseTestCase
{
    /**
     * @dataProvider runDataProvider
     */
    public function testRun(RulesRetriever $rulesRetriever, int $expectedReturnCode)
    {
        $refreshCommand = new RefreshCommand($rulesRetriever);

        $returnCode = $refreshCommand->run(new ArrayInput([]), new NullOutput());

        $this->assertSame($expectedReturnCode, $returnCode);
    }

    public function runDataProvider(): array
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
