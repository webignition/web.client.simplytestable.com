<?php

namespace SimplyTestable\WebClientBundle\Tests\Unit\Services\TaskOutput\ResultParser;

use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Model\TaskOutput\Result;
use SimplyTestable\WebClientBundle\Services\TaskOutput\ResultParser\LinkIntegrityResultParser;
use SimplyTestable\WebClientBundle\Tests\Factory\ModelFactory;

class LinkIntegrityResultParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var LinkIntegrityResultParser
     */
    private $linkIntegrityResultParser;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        parent::setUp();

        $this->linkIntegrityResultParser = new LinkIntegrityResultParser();
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
        $this->linkIntegrityResultParser->setOutput($output);

        $result = $this->linkIntegrityResultParser->getResult();

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
                    ModelFactory::createLinkIntegrityMessage([
                        ModelFactory::LINK_INTEGRITY_MESSAGE_MESSAGE => 'Internal Server Error',
                        ModelFactory::LINK_INTEGRITY_MESSAGE_CLASS => 'http-retrieval-500',
                        ModelFactory::LINK_INTEGRITY_MESSAGE_TYPE => 'error',
                    ]),
                ],
                'expectedResultGetWarnings' => [],
            ],
            'foo' => [
                'output' => ModelFactory::createTaskOutput([
                    ModelFactory::TASK_OUTPUT_CONTENT => json_encode([
                        [
                            'context' => '<link href="/foo.css">',
                            'state' => 200,
                            'type' => 'http',
                            'url' => 'http://example.com/foo.css',
                        ],
                        [
                            'context' => '<a href="/bar">Bar</a>',
                            'state' => 404,
                            'type' => 'http',
                            'url' => 'http://example.com/bar',
                        ],
                        [
                            'context' => '<a href="/logout">Logout</a>',
                            'state' => 28,
                            'type' => 'curl',
                            'url' => 'http://example.com/logout',
                        ],
                    ]),
                ]),
                'expectedResultGetErrors' => [
                    ModelFactory::createLinkIntegrityMessage([
                        ModelFactory::LINK_INTEGRITY_MESSAGE_CONTEXT => '<a href="/bar">Bar</a>',
                        ModelFactory::LINK_INTEGRITY_MESSAGE_URL => 'http://example.com/bar',
                        ModelFactory::LINK_INTEGRITY_MESSAGE_STATE => 404,
                        ModelFactory::LINK_INTEGRITY_MESSAGE_MESSAGE => 'HTTP error 404',
                        ModelFactory::LINK_INTEGRITY_MESSAGE_TYPE => 'error',
                        ModelFactory::LINK_INTEGRITY_MESSAGE_CLASS => 'http',
                    ]),
                    ModelFactory::createLinkIntegrityMessage([
                        ModelFactory::LINK_INTEGRITY_MESSAGE_CONTEXT => '<a href="/logout">Logout</a>',
                        ModelFactory::LINK_INTEGRITY_MESSAGE_URL => 'http://example.com/logout',
                        ModelFactory::LINK_INTEGRITY_MESSAGE_STATE => 28,
                        ModelFactory::LINK_INTEGRITY_MESSAGE_MESSAGE => 'CURL error 28',
                        ModelFactory::LINK_INTEGRITY_MESSAGE_TYPE => 'error',
                        ModelFactory::LINK_INTEGRITY_MESSAGE_CLASS => 'curl',
                    ]),
                ],
                'expectedResultGetWarnings' => [],
            ],
        ];
    }
}
