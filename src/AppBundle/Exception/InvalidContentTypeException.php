<?php

namespace AppBundle\Exception;

class InvalidContentTypeException extends \Exception
{
    const MESSAGE = 'Invalid content type: "%s"';
    const CODE = 0;

    /**
     * @var string
     */
    private $contentType;

    /**
     * @param string $contentType
     */
    public function __construct($contentType)
    {
        $message = sprintf(self::MESSAGE, $contentType);

        parent::__construct($message, self::CODE);

        $this->contentType = $contentType;
    }

    /**
     * @return string
     */
    public function getContentType()
    {
        return $this->contentType;
    }
}
