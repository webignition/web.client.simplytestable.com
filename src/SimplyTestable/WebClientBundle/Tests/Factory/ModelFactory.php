<?php

namespace SimplyTestable\WebClientBundle\Tests\Factory;

use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Model\TaskOutput\HtmlTextFileMessage;

class ModelFactory
{
    const TASK_OUTPUT_CONTENT = 'content';
    const HTML_TEXT_FILE_MESSAGE_COLUMN_NUMBER = 'column_number';
    const HTML_TEXT_FILE_MESSAGE_LINE_NUMBER = 'line_number';
    const HTML_TEXT_FILE_MESSAGE_MESSAGE = 'message';
    const HTML_TEXT_FILE_MESSAGE_TYPE = 'type';
    const HTML_TEXT_FILE_MESSAGE_CLASS = 'class';

    /**
     * @param array $taskOutputValues
     *
     * @return Output
     */
    public static function createTaskOutput($taskOutputValues = [])
    {
        $output = new Output();

        if (isset($taskOutputValues[self::TASK_OUTPUT_CONTENT])) {
            $output->setContent($taskOutputValues[self::TASK_OUTPUT_CONTENT]);
        }

        return $output;
    }

    /**
     * @param array $htmlTextFileMessageValues
     *
     * @return HtmlTextFileMessage
     */
    public static function createHtmlTextFileMessage($htmlTextFileMessageValues = [])
    {
        $htmlTextFileMessage = new HtmlTextFileMessage();

        if (isset($htmlTextFileMessageValues[self::HTML_TEXT_FILE_MESSAGE_COLUMN_NUMBER])) {
            $htmlTextFileMessage->setColumnNumber(
                $htmlTextFileMessageValues[self::HTML_TEXT_FILE_MESSAGE_COLUMN_NUMBER]
            );
        }

        if (isset($htmlTextFileMessageValues[self::HTML_TEXT_FILE_MESSAGE_LINE_NUMBER])) {
            $htmlTextFileMessage->setLineNumber(
                $htmlTextFileMessageValues[self::HTML_TEXT_FILE_MESSAGE_LINE_NUMBER]
            );
        }

        if (isset($htmlTextFileMessageValues[self::HTML_TEXT_FILE_MESSAGE_MESSAGE])) {
            $htmlTextFileMessage->setMessage(
                $htmlTextFileMessageValues[self::HTML_TEXT_FILE_MESSAGE_MESSAGE]
            );
        }

        if (isset($htmlTextFileMessageValues[self::HTML_TEXT_FILE_MESSAGE_TYPE])) {
            $htmlTextFileMessage->setType(
                $htmlTextFileMessageValues[self::HTML_TEXT_FILE_MESSAGE_TYPE]
            );
        }

        if (isset($htmlTextFileMessageValues[self::HTML_TEXT_FILE_MESSAGE_CLASS])) {
            $htmlTextFileMessage->setClass(
                $htmlTextFileMessageValues[self::HTML_TEXT_FILE_MESSAGE_CLASS]
            );
        }

        return $htmlTextFileMessage;
    }
}
