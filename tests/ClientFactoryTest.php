<?php

namespace Chrismou\Discogs\Tests;

use Chrismou\Discogs\ClientFactory;
use Chrismou\Discogs\Tests\AbstractTest;
use PHPUnit_Framework_TestCase;
use \Mockery as m;

class ClientFactoryTest extends PHPUnit_Framework_TestCase
{
    /**
     * @var \Mockery\MockInterface
     */
    protected $mockClient;

    /**
     * @var string
     */
    protected $dummyAccessToken;

    /**
     * @var string
     */
    protected $dummyApplicationIdentifier;

    /**
     * @var \Chrismou\Discogs\ClientFactory
     */
    protected $clientFactory;

    /**
     * Setup the test class
     */
    public function setUp()
    {
        $this->mockClient = m::mock('\GuzzleHttp\ClientInterface');

        $this->dummyAccessToken = 'abcde';

        $this->dummyApplicationIdentifier = 'SuperCoolApp/1.0 +https://github.com/chrismou/php-discogs-wrapper';

        $this->clientFactory = new ClientFactory(
            $this->mockClient,
            $this->dummyAccessToken,
            $this->dummyApplicationIdentifier
        );
    }

    /**
     * @test
     */
    public function it_returns_a_database_service_object()
    {
        $this->assertInstanceOf('Chrismou\Discogs\Discogs', $this->clientFactory->database);
    }
}
