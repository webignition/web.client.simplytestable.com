<?php

namespace Tests\AppBundle\Unit\Services\TaskOutput\ResultParser;

use App\Entity\Task\Output;
use App\Model\TaskOutput\Result;
use App\Services\TaskOutput\ResultParser\HtmlValidationResultParser;
use Tests\AppBundle\Factory\ModelFactory;

class HtmlValidationResultParserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var HtmlValidationResultParser
     */
    private $htmlValidationResultParser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->htmlValidationResultParser = new HtmlValidationResultParser();
    }

    /**
     * @dataProvider handlesDataProvider
     *
     * @param string $taskType
     * @param bool $expectedHandles
     */
    public function testHandles($taskType, $expectedHandles)
    {
        $this->assertEquals($expectedHandles, $this->htmlValidationResultParser->handles($taskType));
    }

    /**
     * @return array
     */
    public function handlesDataProvider()
    {
        return [
            Output::TYPE_HTML_VALIDATION => [
                'taskType' => Output::TYPE_HTML_VALIDATION,
                'expectedHandles' => true,
            ],
            Output::TYPE_CSS_VALIDATION => [
                'taskType' => Output::TYPE_CSS_VALIDATION,
                'expectedHandles' => false,
            ],
            Output::TYPE_JS_STATIC_ANALYSIS => [
                'taskType' => Output::TYPE_JS_STATIC_ANALYSIS,
                'expectedHandles' => false,
            ],
            Output::TYPE_LINK_INTEGRITY => [
                'taskType' => Output::TYPE_LINK_INTEGRITY,
                'expectedHandles' => false,
            ],
        ];
    }

    /**
     * @dataProvider getResultDataProvider
     *
     * @param Output $output
     * @param array $expectedResultGetErrors
     * @param array $expectedResultGetWarnings
     */
    public function testGetResult(
        Output $output,
        array $expectedResultGetErrors,
        array $expectedResultGetWarnings
    ) {
        $this->htmlValidationResultParser->setOutput($output);

        $result = $this->htmlValidationResultParser->getResult();

        $this->assertInstanceOf(Result::class, $result);
        $this->assertEquals($expectedResultGetErrors, $result->getErrors());
        $this->assertEquals($expectedResultGetWarnings, $result->getWarnings());
    }

    /**
     * @return array
     */
    public function getResultDataProvider()
    {
        return [
            'no messages property' => [
                'output' => new Output(),
                'expectedResultGetErrors' => [],
                'expectedResultGetWarnings' => [],
            ],
            'empty messages property' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                        'messages' => [],
                    ]),
                ]),
                'expectedResultGetErrors' => [],
                'expectedResultGetWarnings' => [],
            ],
            'single error' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                        'messages' => [
                            [
                                'lastLine' => 12,
                                'lastColumn' => 13,
                                'message' => 'An img element must have an alt attribute',
                                'messageId' => 'html5',
                                'explanation' => 'foo',
                                'type' => 'error',
                            ],
                        ],
                    ]),
                ]),
                'expectedResultGetErrors' => [
                    ModelFactory::createHtmlTextFileMessage([
                        ModelFactory::HTML_TEXT_FILE_MESSAGE_LINE_NUMBER => 12,
                        ModelFactory::HTML_TEXT_FILE_MESSAGE_COLUMN_NUMBER => 13,
                        ModelFactory::HTML_TEXT_FILE_MESSAGE_MESSAGE => 'An img element must have an alt attribute',
                        ModelFactory::HTML_TEXT_FILE_MESSAGE_TYPE => 'error',
                        ModelFactory::HTML_TEXT_FILE_MESSAGE_CLASS => 'html5',
                    ]),
                ],
                'expectedResultGetWarnings' => [],
            ],
            'single warning' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                        'messages' => [
                            [
                                'lastLine' => 12,
                                'lastColumn' => 13,
                                'message' => 'An img element must have an alt attribute',
                                'messageId' => 'html5',
                                'explanation' => 'foo',
                                'type' => 'warning',
                            ],
                        ],
                    ]),
                ]),
                'expectedResultGetErrors' => [],
                'expectedResultGetWarnings' => [
                    ModelFactory::createHtmlTextFileMessage([
                        ModelFactory::HTML_TEXT_FILE_MESSAGE_LINE_NUMBER => 12,
                        ModelFactory::HTML_TEXT_FILE_MESSAGE_COLUMN_NUMBER => 13,
                        ModelFactory::HTML_TEXT_FILE_MESSAGE_MESSAGE => 'An img element must have an alt attribute',
                        ModelFactory::HTML_TEXT_FILE_MESSAGE_TYPE => 'warning',
                        ModelFactory::HTML_TEXT_FILE_MESSAGE_CLASS => 'html5',
                    ]),
                ],
            ],
            'character encoding error' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                        'messages' => [
                            [
                                'message' => 'foo',
                                'messageId' => 'character-encoding',
                                'type' => 'error',
                            ],
                        ],
                    ]),
                ]),
                'expectedResultGetErrors' => [
                    ModelFactory::createHtmlTextFileMessage([
                        ModelFactory::HTML_TEXT_FILE_MESSAGE_MESSAGE => 'foo',
                        ModelFactory::HTML_TEXT_FILE_MESSAGE_TYPE => 'error',
                        ModelFactory::HTML_TEXT_FILE_MESSAGE_CLASS => 'character-encoding',
                    ]),
                ],
                'expectedResultGetWarnings' => [],
            ],
        ];
    }
}
