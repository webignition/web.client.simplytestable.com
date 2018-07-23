<?php

namespace App\Services\Pdp;

use GuzzleHttp\Client as HttpClient;
use Pdp\Converter;

class RulesRetriever
{
    const PSL_URL = 'https://publicsuffix.org/list/public_suffix_list.dat';

    /**
     * @var string
     */
    private $pspPslDataPath;

    /**
     * @var HttpClient
     */
    private $httpClient;

    /**
     * @param string $pspPslDataPath
     * @param HttpClient $httpClient
     */
    public function __construct($pspPslDataPath, HttpClient $httpClient)
    {
        $this->pspPslDataPath = $pspPslDataPath;
        $this->httpClient = $httpClient;
    }

    /**
     * @return bool|int
     */
    public function retrieve()
    {
        $response = null;

        try {
            $response = $this->httpClient->get(self::PSL_URL);
        } catch (\Exception $exception) {
        }

        if (empty($response)) {
            return false;
        }

        $responseContent = $response->getBody()->getContents();

        $converter = new Converter();
        $rulesArray = $converter->convert($responseContent);

        $cachedRules = json_encode($rulesArray);

        return file_put_contents($this->pspPslDataPath, $cachedRules);
    }
}
