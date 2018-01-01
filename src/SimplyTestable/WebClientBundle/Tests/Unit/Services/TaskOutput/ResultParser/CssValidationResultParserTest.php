<?php

namespace SimplyTestable\WebClientBundle\Tests\Unit\Services\TaskOutput\ResultParser;

use Guzzle\Service\Resource\Model;
use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;
use SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\CssValidationResultParser;
use SimplyTestable\WebClientBundle\Tests\Factory\ModelFactory;

class CssValidationResultParserTest extends \PHPUnit_Framework_TestCase
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
        $this->cssValidationResultParser->setOutput($output);

        $result = $this->cssValidationResultParser->getResult();

        $this->assertInstanceOf(Result::class, $result);
        $this->assertEquals($expectedResultGetErrors, $result->getErrors());
        $this->assertEquals($expectedResultGetWarnings, $result->getWarnings());
    }

    /**
SELECT DISTINCT(TaskOutput.id), TaskOutput.output, TaskOutput.errorCount FROM `TaskOutput`
LEFT JOIN Task ON Task.output_id = TaskOutput.id
WHERE Task.tasktype_id = 2
AND TaskOutput.output LIKE '{"messages"%'
LIMIT 0, 8
     */

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

    // [{"message":"Unknown error","class":"css-validation-exception-unknown","type":"error","context":"","ref":"http:\/\/herve-societe.be\/","line_number":0}]
}
