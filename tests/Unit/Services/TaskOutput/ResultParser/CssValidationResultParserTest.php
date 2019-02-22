<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Services\TaskOutput\ResultParser;

use App\Entity\Task\Output;
use App\Model\TaskOutput\Result;
use App\Services\TaskOutput\ResultParser\CssValidationResultParser;
use App\Tests\Factory\ModelFactory;

class CssValidationResultParserTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @var CssValidationResultParser
     */
    private $cssValidationResultParser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->cssValidationResultParser = new CssValidationResultParser();
    }

    /**
     * @dataProvider handlesDataProvider
     */
    public function testHandles(string $taskType, bool $expectedHandles)
    {
        $this->assertEquals($expectedHandles, $this->cssValidationResultParser->handles($taskType));
    }

    public function handlesDataProvider(): array
    {
        return [
            Output::TYPE_HTML_VALIDATION => [
                'taskType' => Output::TYPE_HTML_VALIDATION,
                'expectedHandles' => false,
            ],
            Output::TYPE_CSS_VALIDATION => [
                'taskType' => Output::TYPE_CSS_VALIDATION,
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
     */
    public function testGetResult(
        Output $output,
        array $expectedResultGetErrors,
        array $expectedResultGetWarnings
    ) {
        $this->cssValidationResultParser->setOutput($output);

        $result = $this->cssValidationResultParser->getResult();

        $this->assertInstanceOf(Result::class, $result);
        $this->assertEquals($expectedResultGetErrors, $result->getErrors());
        $this->assertEquals($expectedResultGetWarnings, $result->getWarnings());
    }

    public function getResultDataProvider(): array
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
            'single failure output' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                        'messages' => [
                            [
                                'message' => 'DNS lookup failure',
                                'messageId' => 'http-retrieval-curl-code-6',
                                'type' => 'error',
                            ]
                        ]
                    ]),
                ]),
                'expectedResultGetErrors' => [
                    ModelFactory::createCssTextFileMessage([
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_MESSAGE => 'DNS lookup failure',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_TYPE => 'error',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_CLASS => 'http-retrieval-curl-code-6',
                    ]),
                ],
                'expectedResultGetWarnings' => [],
            ],
            'single failure output unknown error with class' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                        'messages' => [
                            [
                                'message' => 'unknown error',
                                'type' => 'error',
                                'class' => 'css-validation-exception-unknown',
                                'ref' => 'http://example.com/foo.css'
                            ]
                        ]
                    ]),
                ]),
                'expectedResultGetErrors' => [
                    ModelFactory::createCssTextFileMessage([
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_MESSAGE => 'unknown error',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_TYPE => 'error',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_LINE_NUMBER => 0,
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_CLASS => 'css-validation-exception-unknown',
                    ]),
                ],
                'expectedResultGetWarnings' => [],
            ],
            'single error output' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                        [
                            'message' => 'foo',
                            'context' => '.foo',
                            'line_number' => 108,
                            'type' => 'error',
                            'ref' => 'http://example.com/foo.css',
                        ],
                    ]),
                ]),
                'expectedResultGetErrors' => [
                    ModelFactory::createCssTextFileMessage([
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_MESSAGE => 'foo',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_TYPE => 'error',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_CONTEXT => '.foo',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_REF => 'http://example.com/foo.css',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_LINE_NUMBER => 108,
                    ]),
                ],
                'expectedResultGetWarnings' => [],
            ],
            'double error output' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                        [
                            'message' => 'foo',
                            'context' => '.foo',
                            'line_number' => 108,
                            'type' => 'error',
                            'ref' => 'http://example.com/foo.css',
                        ],
                        [
                            'message' => 'bar',
                            'context' => '.bar',
                            'line_number' => 128,
                            'type' => 'error',
                            'ref' => 'http://example.com/foo.css',
                        ],
                    ]),
                ]),
                'expectedResultGetErrors' => [
                    ModelFactory::createCssTextFileMessage([
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_MESSAGE => 'foo',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_TYPE => 'error',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_CONTEXT => '.foo',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_REF => 'http://example.com/foo.css',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_LINE_NUMBER => 108,
                    ]),
                    ModelFactory::createCssTextFileMessage([
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_MESSAGE => 'bar',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_TYPE => 'error',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_CONTEXT => '.bar',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_REF => 'http://example.com/foo.css',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_LINE_NUMBER => 128,
                    ]),
                ],
                'expectedResultGetWarnings' => [],
            ],
            'single warning output' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                        [
                            'message' => 'foo',
                            'context' => '.foo',
                            'line_number' => 108,
                            'type' => 'warning',
                            'ref' => 'http://example.com/foo.css',
                        ],
                    ]),
                ]),
                'expectedResultGetErrors' => [],
                'expectedResultGetWarnings' => [
                    ModelFactory::createCssTextFileMessage([
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_MESSAGE => 'foo',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_TYPE => 'warning',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_CONTEXT => '.foo',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_REF => 'http://example.com/foo.css',
                        ModelFactory::CSS_TEXT_FILE_MESSAGE_LINE_NUMBER => 108,
                    ]),
                ],
            ],
        ];
    }
}
