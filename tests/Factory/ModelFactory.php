<?php

namespace App\Tests\Factory;

use App\Entity\Task\Output;
use App\Entity\Task\Task;
use App\Model\TaskOutput\CssTextFileMessage;
use App\Model\TaskOutput\HtmlTextFileMessage;
use App\Model\TaskOutput\LinkIntegrityMessage;
use App\Model\TestOptions;
use SimplyTestable\PageCacheBundle\Entity\CacheValidatorHeaders;

class ModelFactory
{
    const TASK_OUTPUT_CONTENT = 'content';
    const TASK_OUTPUT_TYPE = 'type';

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

    const LINK_INTEGRITY_MESSAGE_CONTEXT = 'context';
    const LINK_INTEGRITY_MESSAGE_URL = 'url';
    const LINK_INTEGRITY_MESSAGE_STATE = 'state';
    const LINK_INTEGRITY_MESSAGE_MESSAGE = 'message';
    const LINK_INTEGRITY_MESSAGE_TYPE = 'type';
    const LINK_INTEGRITY_MESSAGE_CLASS = 'class';

    const TEST_OPTIONS_FEATURE_OPTIONS = 'feature_options';

    const TASK_OUTPUT = 'output';
    const TASK_TASK_ID = 'id';
    const TASK_STATE = 'state';

    const CACHE_VALIDATOR_HEADERS_IDENTIFIER = 'identifier';
    const CACHE_VALIDATOR_HEADERS_LAST_MODIFIED_DATE = 'last-modified-date';

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

        if (isset($taskOutputValues[self::TASK_OUTPUT_TYPE])) {
            $output->setType($taskOutputValues[self::TASK_OUTPUT_TYPE]);
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
     * @param array $linkIntegrityMessageValues
     *
     * @return LinkIntegrityMessage
     */
    public static function createLinkIntegrityMessage($linkIntegrityMessageValues = [])
    {
        $linkIntegrityMessage = new LinkIntegrityMessage();

        if (isset($linkIntegrityMessageValues[self::LINK_INTEGRITY_MESSAGE_CONTEXT])) {
            $linkIntegrityMessage->setContext(
                $linkIntegrityMessageValues[self::LINK_INTEGRITY_MESSAGE_CONTEXT]
            );
        }

        if (isset($linkIntegrityMessageValues[self::LINK_INTEGRITY_MESSAGE_URL])) {
            $linkIntegrityMessage->setUrl(
                $linkIntegrityMessageValues[self::LINK_INTEGRITY_MESSAGE_URL]
            );
        }

        if (isset($linkIntegrityMessageValues[self::LINK_INTEGRITY_MESSAGE_STATE])) {
            $linkIntegrityMessage->setState(
                $linkIntegrityMessageValues[self::LINK_INTEGRITY_MESSAGE_STATE]
            );
        }

        if (isset($linkIntegrityMessageValues[self::LINK_INTEGRITY_MESSAGE_MESSAGE])) {
            $linkIntegrityMessage->setMessage(
                $linkIntegrityMessageValues[self::LINK_INTEGRITY_MESSAGE_MESSAGE]
            );
        }

        if (isset($linkIntegrityMessageValues[self::LINK_INTEGRITY_MESSAGE_TYPE])) {
            $linkIntegrityMessage->setType(
                $linkIntegrityMessageValues[self::LINK_INTEGRITY_MESSAGE_TYPE]
            );
        }

        if (isset($linkIntegrityMessageValues[self::LINK_INTEGRITY_MESSAGE_CLASS])) {
            $linkIntegrityMessage->setClass(
                $linkIntegrityMessageValues[self::LINK_INTEGRITY_MESSAGE_CLASS]
            );
        }

        return $linkIntegrityMessage;
    }

    /**
     * @param array $testOptionsValues
     *
     * @return TestOptions
     */
    public static function createTestOptions($testOptionsValues = [])
    {
        $testOptions = new TestOptions();

        if (isset($testOptionsValues[self::TEST_OPTIONS_FEATURE_OPTIONS])) {
            $featureOptionsCollection = $testOptionsValues[self::TEST_OPTIONS_FEATURE_OPTIONS];

            foreach ($featureOptionsCollection as $featureName => $featureOptions) {
                $testOptions->setFeatureOptions($featureName, $featureOptions);
            }
        }

        return $testOptions;
    }

    /**
     * @param array $taskValues
     *
     * @return Task
     */
    public static function createTask($taskValues = [])
    {
        $task = new Task();

        if (isset($taskValues[self::TASK_OUTPUT])) {
            $task->setOutput($taskValues[self::TASK_OUTPUT]);
        }

        if (isset($taskValues[self::TASK_TASK_ID])) {
            $task->setTaskId($taskValues[self::TASK_TASK_ID]);
        }

        if (isset($taskValues[self::TASK_STATE])) {
            $task->setState($taskValues[self::TASK_STATE]);
        }

        return $task;
    }

    /**
     * @param array $cacheValidatorHeadersValues
     *
     * @return CacheValidatorHeaders
     */
    public static function createCacheValidatorHeaders($cacheValidatorHeadersValues = [])
    {
        $cacheValidatorHeaders = new CacheValidatorHeaders();

        $cacheValidatorHeaders->setIdentifier($cacheValidatorHeadersValues[self::CACHE_VALIDATOR_HEADERS_IDENTIFIER]);
        $cacheValidatorHeaders->setLastModifiedDate(
            $cacheValidatorHeadersValues[self::CACHE_VALIDATOR_HEADERS_LAST_MODIFIED_DATE]
        );

        return $cacheValidatorHeaders;
    }
}
