<?php

namespace Chrismou\Discogs\Tests;

use PHPUnit_Framework_TestCase;
use \Mockery as m;

class AbstractTest extends PHPUnit_Framework_TestCase
{
    /**
     * Setup the abstract class
     */
    public function setUp()
    {
    }

    /**
     * Custom teardown to include mockery expectations as assertions
     */
    public function tearDown()
    {
        if ($container = m::getContainer()) {
            $this->addToAssertionCount($container->mockery_getExpectationCount());
        }

        m::close();

        parent::tearDown();
    }
}
