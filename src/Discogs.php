<?php

namespace Chrismou\Discogs;

use Chrismou\Discogs\Exception\NotFoundException;
use GuzzleHttp\ClientInterface;

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
    protected $applicationIdenfier;

    /**
     * @var string
     */
    protected $apiUrl = 'https://api.discogs.com/';

    /**
     * Discogs constructor.
     *
     * @param \GuzzleHttp\ClientInterface $httpClient
     * @param string $accessToken
     * @param string $applicationIdentifer An identifier to use in the User-Agent
     *     See https://www.discogs.com/developers/#page:home,header:home-general-information
     *     As a general rule, aim for something like "MyAppName/1.0 +http://mywebaddress.com"
     */
    public function __construct(ClientInterface $httpClient, $accessToken, $applicationIdentifer)
    {
        $this->httpClient = $httpClient;
        $this->accessToken = $accessToken;
        $this->applicationIdenfier = $applicationIdentifer;
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
     * @param string $masterId
     *
     * @return \stdClass
     * @throws NotFoundException
     */
    public function masterRelease($masterId)
    {
        $uri = 'masters/' . $masterId;

        return $this->doRequest($uri);
    }

    /**
     * @param string $masterId
     *
     * @return \stdClass
     * @throws NotFoundException
     */
    public function masterReleaseVersions($masterId)
    {
        $uri = 'masters/' . $masterId . '/versions';

        return $this->doRequest($uri);
    }

    /**
     * @param string $artistId
     *
     * @return \stdClass
     * @throws NotFoundException
     */
    public function artist($artistId)
    {
        $uri = 'artists/' . $artistId;

        return $this->doRequest($uri);
    }

    /**
     * @param string $artistId
     *
     * @return \stdClass
     * @throws NotFoundException
     */
    public function artistReleases($artistId)
    {
        $uri = 'artists/' . $artistId . '/releases';

        return $this->doRequest($uri);
    }

    /**
     * @param string $labelId
     *
     * @return \stdClass
     * @throws NotFoundException
     */
    public function label($labelId)
    {
        $uri = 'labels/' . $labelId;

        return $this->doRequest($uri);
    }

    /**
     * @param string $labelId
     *
     * @return \stdClass
     * @throws NotFoundException
     */
    public function labelReleases($labelId)
    {
        $uri = 'labels/' . $labelId . '/releases';

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
            $request  = $this->httpClient->request(
                'get',
                $this->buildRequestUrl($uri, $parameters),
                [
                    'headers' => [
                        'User-Agent' => $this->applicationIdenfier,
                    ]
                ]
            );
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
