<?php

namespace Tests\AppBundle\Unit\Services\TaskOutput\ResultParser;

use AppBundle\Entity\Task\Output;
use AppBundle\Model\TaskOutput\Result;
use AppBundle\Services\TaskOutput\ResultParser\JsStaticAnalysisResultParser;
use Tests\AppBundle\Factory\ModelFactory;

class JsStaticAnalysisResultParserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var JsStaticAnalysisResultParser
     */
    private $jsStaticAnalysisResultParser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->jsStaticAnalysisResultParser = new JsStaticAnalysisResultParser();
    }

    /**
     * @dataProvider handlesDataProvider
     *
     * @param string $taskType
     * @param bool $expectedHandles
     */
    public function testHandles($taskType, $expectedHandles)
    {
        $this->assertEquals($expectedHandles, $this->jsStaticAnalysisResultParser->handles($taskType));
    }

    /**
     * @return array
     */
    public function handlesDataProvider()
    {
        return [
            Output::TYPE_HTML_VALIDATION => [
                'taskType' => Output::TYPE_HTML_VALIDATION,
                'expectedHandles' => false,
            ],
            Output::TYPE_CSS_VALIDATION => [
                'taskType' => Output::TYPE_CSS_VALIDATION,
                'expectedHandles' => false,
            ],
            Output::TYPE_JS_STATIC_ANALYSIS => [
                'taskType' => Output::TYPE_JS_STATIC_ANALYSIS,
                'expectedHandles' => true,
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
        $this->jsStaticAnalysisResultParser->setOutput($output);

        $result = $this->jsStaticAnalysisResultParser->getResult();

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
            'null output' => [
                'output' => new Output(),
                'expectedResultGetErrors' => [],
                'expectedResultGetWarnings' => [],
            ],
            'empty output' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([]),
                ]),
                'expectedResultGetErrors' => [],
                'expectedResultGetWarnings' => [],
            ],
            'zero errors' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                        'http://example.com/foo.js' => [
                            'statusLine' => '',
                            'entries' => [],
                        ],
                    ]),
                ]),
                'expectedResultGetErrors' => [],
                'expectedResultGetWarnings' => [],
            ],
            'failure output' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                        'messages' => [
                            [
                                'message' => 'Internal Server Error',
                                'messageId' => 'http-retrieval-500',
                                'type' => 'error'
                            ],
                        ],
                    ]),
                ]),
                'expectedResultGetErrors' => [
                    ModelFactory::createJsTextFileMessage([
                        ModelFactory::JS_TEXT_FILE_MESSAGE_MESSAGE => 'Internal Server Error',
                        ModelFactory::JS_TEXT_FILE_MESSAGE_CLASS => 'http-retrieval-500',
                        ModelFactory::JS_TEXT_FILE_MESSAGE_TYPE => 'error',
                    ]),
                ],
                'expectedResultGetWarnings' => [],
            ],
            'single error' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                        'http://example.com/foo.js' => [
                            'statusLine' => 'http://example.com/foo.js',
                            'entries' => [
                                [
                                    'headerLine' => [
                                        'errorNumber' => 1,
                                        'errorMessage' => '\'window\' was used before it was defined.',
                                    ],
                                    'fragmentLine' => [
                                        'fragment' => 'window.location = \'foo\';',
                                        'lineNumber' => 1,
                                        'columnNumber' => 2,
                                    ],
                                ],
                            ],
                        ],
                    ]),
                ]),
                'expectedResultGetErrors' => [
                    ModelFactory::createJsTextFileMessage([
                        ModelFactory::JS_TEXT_FILE_MESSAGE_CONTEXT => 'http://example.com/foo.js',
                        ModelFactory::JS_TEXT_FILE_MESSAGE_COLUMN_NUMBER => 2,
                        ModelFactory::JS_TEXT_FILE_MESSAGE_LINE_NUMBER => 1,
                        ModelFactory::JS_TEXT_FILE_MESSAGE_FRAGMENT => 'window.location = \'foo\';',
                        ModelFactory::JS_TEXT_FILE_MESSAGE_MESSAGE => '\'window\' was used before it was defined.',
                        ModelFactory::JS_TEXT_FILE_MESSAGE_TYPE => 'error',
                    ]),
                ],
                'expectedResultGetWarnings' => [],
            ],
            'single nested failure output; not found' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                        'http://example.com/foo.js' => [
                            'statusLine' => 'failed',
                            'errorReport' => [
                                'reason' => 'webResourceException',
                                'statusCode' => 404,
                            ],
                        ],
                    ]),
                ]),
                'expectedResultGetErrors' => [
                    ModelFactory::createJsTextFileMessage([
                        ModelFactory::JS_TEXT_FILE_MESSAGE_CONTEXT => 'http://example.com/foo.js',
                        ModelFactory::JS_TEXT_FILE_MESSAGE_MESSAGE => 404,
                        ModelFactory::JS_TEXT_FILE_MESSAGE_TYPE => 'error',
                        ModelFactory::JS_TEXT_FILE_MESSAGE_COLUMN_NUMBER => 0,
                        ModelFactory::JS_TEXT_FILE_MESSAGE_LINE_NUMBER => 0,
                        ModelFactory::JS_TEXT_FILE_MESSAGE_FRAGMENT => 'webResourceException',
                    ]),
                ],
                'expectedResultGetWarnings' => [],
            ],
            'single nested failure output; invalid content type' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                        'http://example.com/foo.js' => [
                            'statusLine' => 'failed',
                            'errorReport' => [
                                'reason' => 'InvalidContentTypeException',
                                'contentType' => 'text/plain',
                            ],
                        ],
                    ]),
                ]),
                'expectedResultGetErrors' => [
                    ModelFactory::createJsTextFileMessage([
                        ModelFactory::JS_TEXT_FILE_MESSAGE_CONTEXT => 'http://example.com/foo.js',
                        ModelFactory::JS_TEXT_FILE_MESSAGE_MESSAGE => 'text/plain',
                        ModelFactory::JS_TEXT_FILE_MESSAGE_TYPE => 'error',
                        ModelFactory::JS_TEXT_FILE_MESSAGE_COLUMN_NUMBER => 0,
                        ModelFactory::JS_TEXT_FILE_MESSAGE_LINE_NUMBER => 0,
                        ModelFactory::JS_TEXT_FILE_MESSAGE_FRAGMENT => 'InvalidContentTypeException',
                    ]),
                ],
                'expectedResultGetWarnings' => [],
            ],
            'nested failure output and non-relevant entry' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                        'http://example.com/foo.js' => [
                            'statusLine' => 'failed',
                            'errorReport' => [
                                'reason' => 'webResourceException',
                                'statusCode' => 404,
                            ],
                        ],
                        'http://example.com/bar.js' => [
                            'errorReport' => [
                                'reason' => 'webResourceException',
                                'statusCode' => 404,
                            ],
                        ],
                    ]),
                ]),
                'expectedResultGetErrors' => [
                    ModelFactory::createJsTextFileMessage([
                        ModelFactory::JS_TEXT_FILE_MESSAGE_CONTEXT => 'http://example.com/foo.js',
                        ModelFactory::JS_TEXT_FILE_MESSAGE_MESSAGE => 404,
                        ModelFactory::JS_TEXT_FILE_MESSAGE_TYPE => 'error',
                        ModelFactory::JS_TEXT_FILE_MESSAGE_COLUMN_NUMBER => 0,
                        ModelFactory::JS_TEXT_FILE_MESSAGE_LINE_NUMBER => 0,
                        ModelFactory::JS_TEXT_FILE_MESSAGE_FRAGMENT => 'webResourceException',
                    ]),
                ],
                'expectedResultGetWarnings' => [],
            ],
        ];
    }

    public function testGetResultErrorReportUnexpectedFailureCondition()
    {
        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Unexpected failure condition');

        $output = ModelFactory::createTaskOutput([
            ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                'http://example.com/foo.js' => [
                    'statusLine' => 'failed',
                    'errorReport' => [
                        'reason' => 'foo',
                    ],
                ],
            ]),
        ]);

        $this->jsStaticAnalysisResultParser->setOutput($output);

        $this->jsStaticAnalysisResultParser->getResult();
    }
}
