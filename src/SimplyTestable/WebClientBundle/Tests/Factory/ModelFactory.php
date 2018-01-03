<?php

namespace SimplyTestable\WebClientBundle\Tests\Factory;

use SimplyTestable\WebClientBundle\Entity\Task\Output;
use SimplyTestable\WebClientBundle\Model\TaskOutput\CssTextFileMessage;
use SimplyTestable\WebClientBundle\Model\TaskOutput\HtmlTextFileMessage;
use SimplyTestable\WebClientBundle\Model\TaskOutput\JsTextFileMessage;

class ModelFactory
{
    const TASK_OUTPUT_CONTENT = 'content';

    const HTML_TEXT_FILE_MESSAGE_COLUMN_NUMBER = 'column_number';
    const HTML_TEXT_FILE_MESSAGE_LINE_NUMBER = 'line_number';
    const HTML_TEXT_FILE_MESSAGE_MESSAGE = 'message';
    const HTML_TEXT_FILE_MESSAGE_TYPE = 'type';
    const HTML_TEXT_FILE_MESSAGE_CLASS = 'class';

    const CSS_TEXT_FILE_MESSAGE_CONTEXT = 'context';
    const CSS_TEXT_FILE_MESSAGE_LINE_NUMBER = 'line_number';
    const CSS_TEXT_FILE_MESSAGE_MESSAGE = 'message';
    const CSS_TEXT_FILE_MESSAGE_REF = 'ref';
    const CSS_TEXT_FILE_MESSAGE_TYPE = 'type';
    const CSS_TEXT_FILE_MESSAGE_CLASS = 'class';

    const JS_TEXT_FILE_MESSAGE_CONTEXT = 'context';
    const JS_TEXT_FILE_MESSAGE_COLUMN_NUMBER = 'column_number';
    const JS_TEXT_FILE_MESSAGE_FRAGMENT = 'fragment';
    const JS_TEXT_FILE_MESSAGE_LINE_NUMBER = 'line_number';
    const JS_TEXT_FILE_MESSAGE_MESSAGE = 'message';
    const JS_TEXT_FILE_MESSAGE_TYPE = 'type';
    const JS_TEXT_FILE_MESSAGE_CLASS = 'class';

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

    /**
     * @param array $cssTextFileMessageValues
     *
     * @return CssTextFileMessage
     */
    public static function createCssTextFileMessage($cssTextFileMessageValues = [])
    {
        $cssTextFileMessage = new CssTextFileMessage();

        if (isset($cssTextFileMessageValues[self::CSS_TEXT_FILE_MESSAGE_CONTEXT])) {
            $cssTextFileMessage->setContext(
                $cssTextFileMessageValues[self::CSS_TEXT_FILE_MESSAGE_CONTEXT]
            );
        }

        if (isset($cssTextFileMessageValues[self::CSS_TEXT_FILE_MESSAGE_LINE_NUMBER])) {
            $cssTextFileMessage->setLineNumber(
                $cssTextFileMessageValues[self::CSS_TEXT_FILE_MESSAGE_LINE_NUMBER]
            );
        }

        if (isset($cssTextFileMessageValues[self::CSS_TEXT_FILE_MESSAGE_MESSAGE])) {
            $cssTextFileMessage->setMessage(
                $cssTextFileMessageValues[self::CSS_TEXT_FILE_MESSAGE_MESSAGE]
            );
        }

        if (isset($cssTextFileMessageValues[self::CSS_TEXT_FILE_MESSAGE_REF])) {
            $cssTextFileMessage->setRef(
                $cssTextFileMessageValues[self::CSS_TEXT_FILE_MESSAGE_REF]
            );
        }

        if (isset($cssTextFileMessageValues[self::CSS_TEXT_FILE_MESSAGE_TYPE])) {
            $cssTextFileMessage->setType(
                $cssTextFileMessageValues[self::CSS_TEXT_FILE_MESSAGE_TYPE]
            );
        }

        if (isset($cssTextFileMessageValues[self::CSS_TEXT_FILE_MESSAGE_CLASS])) {
            $cssTextFileMessage->setClass(
                $cssTextFileMessageValues[self::CSS_TEXT_FILE_MESSAGE_CLASS]
            );
        }

        return $cssTextFileMessage;
    }

    /**
     * @param array $jsTextFileMessageValues
     *
     * @return JsTextFileMessage
     */
    public static function createJsTextFileMessage($jsTextFileMessageValues = [])
    {
        $jsTextFileMessage = new JsTextFileMessage();

        if (isset($jsTextFileMessageValues[self::JS_TEXT_FILE_MESSAGE_CONTEXT])) {
            $jsTextFileMessage->setContext(
                $jsTextFileMessageValues[self::JS_TEXT_FILE_MESSAGE_CONTEXT]
            );
        }

        if (isset($jsTextFileMessageValues[self::JS_TEXT_FILE_MESSAGE_COLUMN_NUMBER])) {
            $jsTextFileMessage->setColumnNumber(
                $jsTextFileMessageValues[self::JS_TEXT_FILE_MESSAGE_COLUMN_NUMBER]
            );
        }

        if (isset($jsTextFileMessageValues[self::JS_TEXT_FILE_MESSAGE_FRAGMENT])) {
            $jsTextFileMessage->setFragment(
                $jsTextFileMessageValues[self::JS_TEXT_FILE_MESSAGE_FRAGMENT]
            );
        }

        if (isset($jsTextFileMessageValues[self::JS_TEXT_FILE_MESSAGE_LINE_NUMBER])) {
            $jsTextFileMessage->setLineNumber(
                $jsTextFileMessageValues[self::JS_TEXT_FILE_MESSAGE_LINE_NUMBER]
            );
        }

        if (isset($jsTextFileMessageValues[self::JS_TEXT_FILE_MESSAGE_MESSAGE])) {
            $jsTextFileMessage->setMessage(
                $jsTextFileMessageValues[self::JS_TEXT_FILE_MESSAGE_MESSAGE]
            );
        }

        if (isset($jsTextFileMessageValues[self::JS_TEXT_FILE_MESSAGE_TYPE])) {
            $jsTextFileMessage->setType(
                $jsTextFileMessageValues[self::JS_TEXT_FILE_MESSAGE_TYPE]
            );
        }

        if (isset($jsTextFileMessageValues[self::JS_TEXT_FILE_MESSAGE_CLASS])) {
            $jsTextFileMessage->setClass(
                $jsTextFileMessageValues[self::JS_TEXT_FILE_MESSAGE_CLASS]
            );
        }

        return $jsTextFileMessage;
    }
}
