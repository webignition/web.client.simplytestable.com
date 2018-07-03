<?php

namespace Tests\WebClientBundle\Services;

use Psr\Http\Message\RequestInterface;
use webignition\HttpHistoryContainer\Container as HttpHistoryContainer;

class PostmarkMessageVerifier
{
    /**
     * @var HttpHistoryContainer
     */
    private $httpHistoryContainer;

    /**
     * @param HttpHistoryContainer $httpHistoryContainer
     */
    public function __construct(HttpHistoryContainer $httpHistoryContainer)
    {
        $this->httpHistoryContainer = $httpHistoryContainer;
    }

    public function verify(array $emailProperties, RequestInterface $emailHttpRequest)
    {
        $messageData = json_decode($emailHttpRequest->getBody()->getContents(), true);

        $requiredEqualityKeys = ['From', 'To', 'Subject'];

        foreach ($requiredEqualityKeys as $key) {
            if ($emailProperties[$key] !== $messageData[$key]) {
                return sprintf(
                    '%s: "%s" equals "%s"',
                    $key,
                    $emailProperties[$key],
                    $messageData[$key]
                );
            }
        }

        foreach ($emailProperties['TextBody'] as $shouldContain) {
            if (!substr_count($messageData['TextBody'], $shouldContain)) {
                return sprintf(
                    'TextBody: contains "%s"',
                    $shouldContain
                );
            }
        }

        return true;
    }
}
