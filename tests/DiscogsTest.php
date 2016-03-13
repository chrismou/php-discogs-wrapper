<?php

namespace Chrismou\Discogs\Tests;

use Chrismou\Discogs\Discogs;
use Chrismou\Discogs\Tests\AbstractTest;
use GuzzleHttp\Psr7\Response;
use GuzzleHttp\Exception\ClientException;
use PHPUnit_Framework_TestCase;
use \Mockery as m;

class DiscogsTest extends PHPUnit_Framework_TestCase
{
    /** @var \Mockery\MockInterface */
    protected $mockClient;

    /** @var \Mockery\MockInterface */
    protected $mockPsr7Response;

    /** @var \Chrismou\Discogs\Discogs  */
    protected $discogs;

    /** @var string */
    protected $apiResponse;

    /** @var string */
    protected $apiUrl;

    /** @var string */
    protected $dummyAccessToken;

    /** @var string */
    protected $dummyId;

    /** @var array */
    protected $dummyResponseHeaders;

    /** @var string */
    protected $dummyApplicationIdentifier;

    /**
     * Setup the test class
     */
    public function setUp()
    {
        $this->mockClient = m::mock('\GuzzleHttp\ClientInterface', [
            'request' => null,
        ]);

        $this->mockPsr7Response = m::mock('GuzzleHttp\Psr7\Response', [
            'getBody' => null,
        ]);

        $this->apiResponse = json_encode(["key" => "value"]);

        $this->apiUrl = 'https://api.discogs.com/';

        $this->dummyAccessToken = 'abcde';

        $this->dummyId = '12';

        $this->dummyApplicationIdentifier = 'SuperCoolApp/1.0 +https://github.com/chrismou/php-discogs-wrapper';

        $this->discogs = new Discogs($this->mockClient, $this->dummyAccessToken, $this->dummyApplicationIdentifier);
    }

    /**
     * @test
     */
    public function it_returns_a_release()
    {
        $uri = 'releases/' . $this->dummyId;

        $response = $this->defaultHttpClientExpectations($uri);

        $this->assertEquals($response, $this->discogs->release($this->dummyId));
    }

    /**
     * @test
     */
    public function it_returns_a_master_release()
    {
        $uri = 'masters/' . $this->dummyId;

        $response = $this->defaultHttpClientExpectations($uri);

        $this->assertEquals($response, $this->discogs->masterRelease($this->dummyId));
    }

    /**
     * @test
     */
    public function it_returns_a_master_release_version()
    {
        $uri = 'masters/' . $this->dummyId . '/versions';

        $response = $this->defaultHttpClientExpectations($uri);

        $this->assertEquals($response, $this->discogs->masterReleaseVersions($this->dummyId));
    }

    /**
     * @test
     */
    public function it_returns_an_artist()
    {
        $uri = 'artists/' . $this->dummyId;

        $response = $this->defaultHttpClientExpectations($uri);

        $this->assertEquals($response, $this->discogs->artist($this->dummyId));
    }

    /**
     * @test
     */
    public function it_returns_artist_releases()
    {
        $uri = 'artists/' . $this->dummyId . '/releases';

        $response = $this->defaultHttpClientExpectations($uri);

        $this->assertEquals($response, $this->discogs->artistReleases($this->dummyId));
    }

    /**
     * @test
     */
    public function it_returns_a_label()
    {
        $uri = 'labels/' . $this->dummyId;

        $response = $this->defaultHttpClientExpectations($uri);

        $this->assertEquals($response, $this->discogs->label($this->dummyId));
    }

    /**
     * @test
     */
    public function it_returns_label_releases()
    {
        $uri = 'labels/' . $this->dummyId . '/releases';

        $response = $this->defaultHttpClientExpectations($uri);

        $this->assertEquals($response, $this->discogs->labelReleases($this->dummyId));
    }

    /**
     * @test
     */
    public function it_returns_a_search_result()
    {
        $params = [
            'artist' => 'mou',
            'genre' => 'rock',
        ];

        $uri = 'database/search?artist=mou&genre=rock';

        $response = $this->defaultHttpClientExpectations($uri);

        $this->assertEquals($response, $this->discogs->search($params));
    }

    /**
     * @test
     * @expectedException \Chrismou\Discogs\Exception\NotFoundException
     * @expectedExceptionMessage Artist Not Found
     */
    public function it_throws_a_notfound_exception_if_artist_is_not_found()
    {
        $uri = 'artists/' . $this->dummyId;

        $response = new \stdClass();
        $response->message = 'Artist Not Found';

        $this->mockPsr7Response = new Response(
            404,
            [],
            json_encode($response)
        );

        $exception = new ClientException(
            'error',
            m::mock('Psr\Http\Message\RequestInterface'),
            $this->mockPsr7Response
        );

        $this->mockClient->shouldReceive('request')
            ->once()
            ->andThrow($exception);

        $this->assertEquals($response, $this->discogs->artist($this->dummyId));
    }

    /**
     * @param string $uri
     * @param string $method
     *
     * @return \stdClass
     */
    protected function defaultHttpClientExpectations($uri, $method = 'get')
    {
        $response = new \stdClass();
        $response->param1 = 'value1';

        $this->mockPsr7Response = new Response(
            200,
            [],
            json_encode($response)
        );

        $this->mockClient->shouldReceive('request')
            ->once()
            ->with(
                $method,
                $this->buildRequestUrl($uri),
                [
                    'headers' => [
                        'User-Agent' => $this->dummyApplicationIdentifier,
                    ]
                ]
            )
            ->andReturn($this->mockPsr7Response);

        return $response;
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
                'token' => $this->dummyAccessToken
            ],
            $parameters
        );
    }
}
