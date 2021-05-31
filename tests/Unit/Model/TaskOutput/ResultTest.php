<?php
/** @noinspection PhpDocSignatureInspection */

namespace App\Tests\Unit\Model\TaskOutput;

use App\Model\TaskOutput\Result;
use App\Model\TaskOutput\TextFileMessage;

class ResultTest extends \PHPUnit\Framework\TestCase
{
    /**
     * @dataProvider isInvalidCharacterEncodingFailureDataProvider
     */
    public function testIsInvalidCharacterEncodingFailure(
        TextFileMessage $message,
        bool $expectedIsInvalidCharacterEncodingFailure
    ) {
        $result = new Result();
        $result->addMessage($message);

        $this->assertEquals($expectedIsInvalidCharacterEncodingFailure, $result->isInvalidCharacterEncodingFailure());
    }

    public function isInvalidCharacterEncodingFailureDataProvider(): array
    {
        return [
            'document type missing is not invalid character encoding failure' => [
                'message' => $this->createTextFileMessage('document-type-missing', ''),
                'expectedIsInvalidCharacterEncodingFailure' => false,
            ],
            'html validator character encoding failure is not invalid character encoding failure' => [
                'message' => $this->createTextFileMessage('character-encoding', ''),
                'expectedIsInvalidCharacterEncodingFailure' => false,
            ],
            'invalid character encoding failure is not invalid character encoding failure' => [
                'message' => $this->createTextFileMessage('invalid-character-encoding', ''),
                'expectedIsInvalidCharacterEncodingFailure' => true,
            ],
        ];
    }

    private function createTextFileMessage(string $class, string $content): TextFileMessage
    {
        $message = new TextFileMessage();
        $message->setClass($class);
        $message->setMessage($content);
        $message->setType('error');

        return $message;
    }
}
