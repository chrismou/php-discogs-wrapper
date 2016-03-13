<?php

namespace Chrismou\Discogs;

use Chrismou\Discogs\Discogs;
use GuzzleHttp\ClientInterface;

class ClientFactory
{
    /**
     * @var \Chrismou\Discogs\Discogs
     */
    public $database;

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
        $this->database = new Discogs(
            $httpClient,
            $accessToken,
            $applicationIdentifer
        );
    }
}
