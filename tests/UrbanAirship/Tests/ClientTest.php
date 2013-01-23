<?php

namespace UrbanAirship\Tests;

use UrbanAirship\Tests\UrbanAirshipTestCase;
use Guzzle\Http\Message\Response;

class ClientTest extends UrbanAirshipTestCase
{
    public function testIsGettingDeviceList()
    {
        $response = new Response(
            200,
            array('Content-Type' => 'application/json'),
            '{"device_tokens_count": 100,"device_tokens": [{"device_token": "0006F3B417B5289B0116D6F12852E18346B5FF988A460A1EB947131C40BFFFFA","active": true,"alias": "Crazy","tags": ["abc", "123"]}],"active_device_tokens_count": 98}'
        );
        $client = $this->getClientMock($response);
        $devices = $client->getDeviceList();

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/device_tokens/', $request->getPath());
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
        $this->assertEquals(100, $devices->getTotal());
        $this->assertEquals(98, $devices->getActive());

        foreach ($devices as $device) {
            $this->assertEquals($device->getId(), '0006F3B417B5289B0116D6F12852E18346B5FF988A460A1EB947131C40BFFFFA');
            $this->assertTrue($device->getActive());
            $this->assertEquals($device->getAlias(), 'Crazy');
            $this->assertEquals($device->getTags(), array('abc', '123'));
        }
    }

    public function testIsGettingPagedDeviceList()
    {
        $response = new Response(
            200,
            array('Content-Type' => 'application/json'),
            '{"next_page": "/device_tokens/?start=FAF62E0BD629D2A498826C5AC14B857EB4072629E1D20C1902CBD66F2354730A&limit=2000","device_tokens_count": 5087,"device_tokens": [{"device_token": "0006F3B417B5289B0116D6F12852E18346B5FF988A460A1EB947131C40BFFFFA","active": true,"alias": "Crazy","tags": ["abc", "123"]}],"active_device_tokens_count": 4821}'
        );

        $response2 = new Response(
            200,
            array('Content-Type' => 'application/json'),
            '{"next_page": "/device_tokens/?start=FAF62E0BD629D2A498826C5AC14B857EB4072629E1D20C1902CBD66F2354730A&limit=2000","device_tokens_count": 5087,"device_tokens": [{"device_token": "0006F3B417B5289B0116D6F12852E18346B5FF988A460A1EB947131C40BFFFFA","active": true,"alias": "Crazy","tags": ["abc", "123"]}],"active_device_tokens_count": 4821}'
        );

        $response3 = new Response(
            200,
            array('Content-Type' => 'application/json'),
            '{"device_tokens_count": 5087,"device_tokens": [{"device_token": "0006F3B417B5289B0116D6F12852E18346B5FF988A460A1EB947131C40BFFFFA","active": true,"alias": "Crazy","tags": ["abc", "123"]}],"active_device_tokens_count": 4821}'
        );
        $client = $this->getClientMock($response);
        $this->mockPlugin->addResponse($response2);
        $this->mockPlugin->addResponse($response3);
        $devices = $client->getDeviceList();

        $this->assertEquals(3, count($devices));
        $this->assertEquals(5087, $devices->getTotal());
        $this->assertEquals(4821, $devices->getActive());

        foreach ($this->getAllMockedRequests() as $request) {
            $this->assertEquals('go.urbanairship.com', $request->getHost());
            $this->assertEquals('GET', $request->getMethod());
            $this->assertEquals('abc', $request->getUsername());
            $this->assertEquals('xyz', $request->getPassword());
        }

        foreach ($devices as $device) {
            $this->assertEquals($device->getId(), '0006F3B417B5289B0116D6F12852E18346B5FF988A460A1EB947131C40BFFFFA');
            $this->assertTrue($device->getActive());
            $this->assertEquals($device->getAlias(), 'Crazy');
            $this->assertEquals($device->getTags(), array('abc', '123'));
        }
    }

    public function testIsGettingDeviceCount()
    {
        $response = new Response(
            200,
            array('Content-Type' => 'application/json'),
            '{"device_tokens_count": 100,"active_device_tokens_count": 98}'
        );
        $client = $this->getClientMock($response);
        $devices = $client->getDeviceCount();

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/device_tokens/count/', $request->getPath());
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
        $this->assertEquals(100, $devices->getTotal());
        $this->assertEquals(98, $devices->getActive());
    }
}