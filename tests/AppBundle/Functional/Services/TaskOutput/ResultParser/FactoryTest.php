<?php

namespace Tests\AppBundle\Functional\Services\TaskOutput\ResultParser;

use AppBundle\Entity\Task\Output;
use AppBundle\Services\TaskOutput\ResultParser\CssValidationResultParser;
use AppBundle\Services\TaskOutput\ResultParser\Factory as ResultParserFactory;
use AppBundle\Services\TaskOutput\ResultParser\HtmlValidationResultParser;
use AppBundle\Services\TaskOutput\ResultParser\JsStaticAnalysisResultParser;
use AppBundle\Services\TaskOutput\ResultParser\LinkIntegrityResultParser;
use Tests\AppBundle\Factory\ModelFactory;
use Tests\AppBundle\Functional\AbstractBaseTestCase;

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
