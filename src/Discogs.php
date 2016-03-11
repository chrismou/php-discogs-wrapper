<?php

namespace Chrismou\Discogs;

use Chrismou\Discogs\Exception\NotFoundException;

class Discogs
{
    /**
     * @var \GuzzleHttp\ClientInterface
     */
    protected $httpClient;

    /**
     * @var string
     */
    protected $accessToken;

    /**
     * @var string
     */
    protected $apiUrl = 'https://api.discogs.com/';

    /**
     * Discogs constructor.
     *
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @param $accessToken
     */
    public function __construct(\GuzzleHttp\ClientInterface $httpClient, $accessToken)
    {
        $this->httpClient = $httpClient;
        $this->accessToken = $accessToken;
    }

    /**
     * @param string $artistId
     *
     * @return string
     * @throws NotFoundException
     */
    public function artist($artistId)
    {
        $uri = 'artists/' . $artistId;

        return $this->doRequest($uri);
    }

    /**
     * @param string $releaseId
     *
     * @return \stdClass
     * @throws NotFoundException
     */
    public function release($releaseId)
    {
        $uri = 'releases/' . $releaseId;

        return $this->doRequest($uri);
    }

    /**
     * @param array $params
     *
     * @return \stdClass
     * @throws NotFoundException
     */
    public function search(array $params = [])
    {
        $uri = 'database/search?' . http_build_query($params);

        return $this->doRequest($uri);
    }

    /**
     * @param string $uri
     * @param array $parameters
     *
     * @return \stdClass
     * @throws NotFoundException
     */
    protected function doRequest($uri, array $parameters = [])
    {
        try {
            $request  = $this->httpClient->get($this->buildRequestUrl($uri, $parameters));
            $response = $request->getBody();
        } catch (\GuzzleHttp\Exception\ClientException $e) {
            throw new NotFoundException(json_decode($e->getResponse()->getBody())->message);
        }

        return json_decode($response);
    }

    /**
     * @param string $uri
     * @param array $parameters
     *
     * @return string
     */
    protected function buildRequestUrl($uri, array $parameters = [])
    {
        return $this->apiUrl . $uri . '?' . http_build_query($this->buildParametersArray($parameters));
    }


    /**
     * @param $parameters
     *
     * @return array
     */
    protected function buildParametersArray(array $parameters = [])
    {
        return array_merge(
            [
                'token' => $this->accessToken
            ],
            $parameters
        );
    }
}
