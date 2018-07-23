<?php
namespace App\Services;

use webignition\NormalisedUrl\NormalisedUrl;

class UrlViewValuesService
{
    /**
     * @param string|null $url
     *
     * @return string[]
     */
    public function create($url = null)
    {
        if (empty($url) || empty(trim($url))) {
            return [];
        }

        $websiteUrl = new NormalisedUrl($url);
        $websiteUrl->getConfiguration()->enableConvertIdnToUtf8();

        $utf8Raw = (string)$websiteUrl;
        $utf8Truncated_40 = $this->getTruncatedString($utf8Raw, 40);
        $utf8Truncated_50 = $this->getTruncatedString($utf8Raw, 50);
        $utf8Truncated_64 = $this->getTruncatedString($utf8Raw, 64);

        $utf8Schemeless = $this->trimUrl($utf8Raw);
        $utf8SchemelessTruncated_40 = $this->getTruncatedString($utf8Schemeless, 40);
        $utf8SchemelessTruncated_50 = $this->getTruncatedString($utf8Schemeless, 50);
        $utf8SchemelessTruncated_64 = $this->getTruncatedString($utf8Schemeless, 64);

        return [
            'raw' => $url,
            'utf8' => [
                'raw' => $utf8Raw,
                'truncated_40' => $utf8Truncated_40,
                'truncated_50' => $utf8Truncated_50,
                'truncated_64' => $utf8Truncated_64,
                'is_truncated_40' => ($utf8Raw != $utf8Truncated_40),
                'is_truncated_50' => ($utf8Raw != $utf8Truncated_50),
                'is_truncated_64' => ($utf8Raw != $utf8Truncated_64),
                'schemeless' => [
                    'raw' => $utf8Schemeless,
                    'truncated_40' => $utf8SchemelessTruncated_40,
                    'truncated_50' => $utf8SchemelessTruncated_50,
                    'truncated_64' => $utf8SchemelessTruncated_64,
                    'is_truncated_40' => ($utf8Schemeless != $utf8SchemelessTruncated_40),
                    'is_truncated_50' => ($utf8Schemeless != $utf8SchemelessTruncated_50),
                    'is_truncated_64' => ($utf8Schemeless != $utf8SchemelessTruncated_64)
                ]
            ]
        ];
    }

    /**
     * @param string $url
     *
     * @return bool|string
     */
    private function trimUrl($url)
    {
        $url = $this->getSchemelessUrl($url);

        if (substr($url, strlen($url) - 1) == '/') {
            $url = substr($url, 0, strlen($url) - 1);
        }

        return $url;
    }

    /**
     * @param string $input
     * @param int $maxLength
     *
     * @return string
     */
    private function getTruncatedString($input, $maxLength = 64)
    {
        if (mb_strlen($input) <= $maxLength) {
            return $input;
        }

        return mb_substr($input, 0, $maxLength);
    }

    /**
     * @param string $url
     *
     * @return string
     */
    private function getSchemelessUrl($url)
    {
        $schemeMarkers = [
            'http://',
            'https://'
        ];

        foreach ($schemeMarkers as $schemeMarkerIndex => $schemeMarker) {
            if (substr($url, 0, strlen($schemeMarker)) == $schemeMarker) {
                return substr($url, strlen($schemeMarker));
            }
        }

        return $url;
    }
}
