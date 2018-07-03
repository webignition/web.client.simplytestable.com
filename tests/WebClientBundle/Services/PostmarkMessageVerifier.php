<?php

namespace Tests\WebClientBundle\Services;

use GuzzleHttp\Psr7;
use Psr\Http\Message\RequestInterface;

class PostmarkMessageVerifier
{
    /**
     * @param array $emailProperties
     * @param RequestInterface $emailHttpRequest
     *
     * @return bool|string
     */
    public function verify(array $emailProperties, RequestInterface $emailHttpRequest)
    {
        $messageData = json_decode($emailHttpRequest->getBody()->getContents(), true);
        Psr7\rewind_body($emailHttpRequest);

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

    /**
     * @param RequestInterface $request
     *
     * @return bool|string
     */
    public function isPostmarkRequest(RequestInterface $request)
    {
        if ('application/json' !== $request->getHeaderLine('content-type')) {
            return 'content-type is "application/json"';
        }

        $messageData = json_decode($request->getBody()->getContents(), true);
        Psr7\rewind_body($request);

        if (!is_array($messageData)) {
            return 'message data is an array';
        }

        $expectedKeys = ['From', 'To', 'Subject', 'TextBody'];
        foreach ($expectedKeys as $key) {
            if (!array_key_exists($key, $messageData)) {
                return 'message data contains key "' . $key . '"';
            }
        }

        return true;
    }
}
