<?php

namespace UrbanAirship\Tests;

use UrbanAirship\Client;
use UrbanAirship\Device\BlackberryDevice;
use Guzzle\Tests\GuzzleTestCase;
use Guzzle\Plugin\Mock\MockPlugin;
use Guzzle\Http\Message\Response;
use Guzzle\Http\Client as HttpClient;

class UrbanAirshipTestCase extends GuzzleTestCase
{
    protected $mockPlugin;

    protected function getClientMock($response)
    {
        $this->mockPlugin = new MockPlugin();
        $this->mockPlugin->addResponse($response);

        $http = new HttpClient('https://go.urbanairship.com/api');
        $http->addSubscriber($this->mockPlugin);

        $client = new Client('abc', 'xyz');
        $client->setClient($http);

        return $client;
    }

    protected function getMockPlugin()
    {
        return $this->mockPlugin;
    }

    protected function getAllMockedRequests()
    {
        return $this->mockPlugin->getReceivedRequests();
    }

    protected function getLastMockedRequest()
    {
        $requests = $this->mockPlugin->getReceivedRequests();
        return $requests[0];
    }
}