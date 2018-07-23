<?php

namespace App\Exception\MailChimp;

use App\Model\MailChimp\ApiError;

abstract class AbstractException extends \Exception
{
    /**
     * @var ApiError
     */
    private $apiError;

    /**
     * @param ApiError $apiError
     */
    public function __construct(ApiError $apiError)
    {
        parent::__construct($apiError->getDetail());

        $this->apiError = $apiError;
    }

    /**
     * @return ApiError
     */
    public function getApiError()
    {
        return $this->apiError;
    }
}
