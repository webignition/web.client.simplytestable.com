<?php

namespace AppBundle\Model\MailChimp;

class ApiError
{
    const TITLE_MEMBER_EXISTS = 'Member Exists';
    const TITLE_RESOURCE_NOT_FOUND = 'Resource Not Found';

    /**
     * @var array
     */
    private $apiErrorData;

    /**
     * @param array $apiErrorData
     */
    public function __construct(array $apiErrorData)
    {
        $this->apiErrorData = $apiErrorData;
    }

    /**
     * @return string
     */
    public function getDetail()
    {
        return $this->apiErrorData['detail'];
    }

    /**
     * @return bool
     */
    public function isMemberExistsError()
    {
        return self::TITLE_MEMBER_EXISTS === $this->apiErrorData['title'];
    }

    /**
     * @return bool
     */
    public function isResourceNotFoundError()
    {
        return self::TITLE_RESOURCE_NOT_FOUND === $this->apiErrorData['title'];
    }
}
