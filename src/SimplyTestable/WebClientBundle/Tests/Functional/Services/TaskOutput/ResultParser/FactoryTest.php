<?php

namespace SimplyTestable\WebClientBundle\Tests\Functional\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\CssValidationResultParser;
use SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\Factory;
use SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\HtmlValidationResultParser;
use SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\JsStaticAnalysisResultParser;
use SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\LinkIntegrityResultParser;
use SimplyTestable\WebClientBundle\Tests\Factory\ModelFactory;
use SimplyTestable\WebClientBundle\Tests\Functional\BaseSimplyTestableTestCase;

class FactoryTest extends BaseSimplyTestableTestCase
{
    /**
     * @var Factory
     */
    private $factory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->factory = $this->container->get('simplytestable.services.taskoutputresultparserfactoryservice');
    }

    /**
     * @dataProvider getParserDataProvider
     *
     * @param Output $output
     * @param string $expectedResultParserClassName
     */
    public function testGetParser(Output $output, $expectedResultParserClassName)
    {
        $resultParser = $this->factory->getParser($output);

        $this->assertInstanceOf($expectedResultParserClassName, $resultParser);
    }

    /**
     * @return array
     */
    public function getParserDataProvider()
    {
        return [
            'HTML validation' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_TYPE => 'HTML validation',
                ]),
                'expectedResultParserClassName' => HtmlValidationResultParser::class,
            ],
            'CSS validation' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_TYPE => 'CSS validation',
                ]),
                'expectedResultParserClassName' => CssValidationResultParser::class,
            ],
            'JS static analysis' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_TYPE => 'JS static analysis',
                ]),
                'expectedResultParserClassName' => JsStaticAnalysisResultParser::class,
            ],
            'Link integrity' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_TYPE => 'Link integrity',
                ]),
                'expectedResultParserClassName' => LinkIntegrityResultParser::class,
            ],
        ];
    }
}
