<?php

namespace Tests\WebClientBundle\Functional\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\CssValidationResultParser;
use SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\Factory as ResultParserFactory;
use SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\HtmlValidationResultParser;
use SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\JsStaticAnalysisResultParser;
use SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\LinkIntegrityResultParser;
use Tests\WebClientBundle\Factory\ModelFactory;
use Tests\WebClientBundle\Functional\AbstractBaseTestCase;

class FactoryTest extends AbstractBaseTestCase
{
    /**
     * @var ResultParserFactory
     */
    private $factory;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->factory = self::$container->get(ResultParserFactory::class);
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
                    ModelFactory::TASK_OUTPUT_TYPE => Output::TYPE_HTML_VALIDATION,
                ]),
                'expectedResultParserClassName' => HtmlValidationResultParser::class,
            ],
            'CSS validation' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_TYPE => Output::TYPE_CSS_VALIDATION,
                ]),
                'expectedResultParserClassName' => CssValidationResultParser::class,
            ],
            'JS static analysis' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_TYPE => Output::TYPE_JS_STATIC_ANALYSIS,
                ]),
                'expectedResultParserClassName' => JsStaticAnalysisResultParser::class,
            ],
            'Link integrity' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_TYPE => Output::TYPE_LINK_INTEGRITY,
                ]),
                'expectedResultParserClassName' => LinkIntegrityResultParser::class,
            ],
        ];
    }

    public function testGetParserForUnknownTaskType()
    {
        $this->assertNull($this->factory->getParser(ModelFactory::createTaskOutput([
            ModelFactory::TASK_OUTPUT_TYPE => 'Foo',
        ])));
    }
}
