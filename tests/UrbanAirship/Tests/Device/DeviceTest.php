<?php

namespace UrbanAirship\Tests\Device;

use UrbanAirship\Tests\UrbanAirshipTestCase;
use UrbanAirship\Device\BlackberryDevice;
use UrbanAirship\Device\AppleDevice;
use Guzzle\Http\Message\Response;

class DeviceTest extends UrbanAirshipTestCase
{
    public function testIsRegisteringBlackberryDevice()
    {
        $client = $this->getClientMock(new Response(200));
        $device = new BlackberryDevice('123456789');
        $client->register($device);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/device_pins/123456789', $request->getPath());
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
    }

    public function testIsRegisteringBlackberryDeviceWithPayload()
    {
        $client = $this->getClientMock(new Response(200));
        $device = new BlackberryDevice('123456789');
        $device->setAlias('Luke Skywalker')->addTag('republic')->addTag('pilot');
        $client->register($device);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/device_pins/123456789', $request->getPath());
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
        $this->assertEquals('application/json', $request->getHeader('Content-Type', true));
        $this->assertEquals('{"alias":"Luke Skywalker","tags":["republic","pilot"]}', $request->getBody()->__toString());
    }

    public function testIsDeactivatingBlackberryDevice()
    {
        $client = $this->getClientMock(new Response(200));
        $device = new BlackberryDevice('123456789');
        $client->deactivate($device);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/device_pins/123456789', $request->getPath());
        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
    }

    public function testIsGettingBlackberryDeviceInfo()
    {
        $response = new Response(
            200,
            array('Content-Type' => 'application/json'),
            '{"device_pin":"123456789","alias":"Luke Skywalker","last_registration":"2009-11-0620:41:06","created":"2009-11-0620:41:06","active":true,"tags":["tag1","tag2"]}'
        );
        $client = $this->getClientMock($response);
        $device = new BlackberryDevice('123456789');
        $output = $client->getInfo($device);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/device_pins/123456789', $request->getPath());
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
        $this->assertEquals('123456789', $output['device_pin']);
        $this->assertEquals('Luke Skywalker', $output['alias']);
        $this->assertEquals('2009-11-0620:41:06', $output['last_registration']);
        $this->assertEquals('2009-11-0620:41:06', $output['created']);
        $this->assertEquals(true, $output['active']);
        $this->assertEquals(array('tag1', 'tag2'), $output['tags']);
    }

    public function testIsRegisteringAppleDevice()
    {
        $client = $this->getClientMock(new Response(200));
        $device = new AppleDevice('123456789');
        $client->register($device);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/device_tokens/123456789', $request->getPath());
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
    }

    public function testIsRegisteringAppleDeviceWithPayload()
    {
        $client = $this->getClientMock(new Response(200));
        $device = new AppleDevice('123456789');
        $device
            ->setAlias('Luke Skywalker')
            ->addTag('republic')
            ->addTag('pilot')
            ->setBadge(1)
            ->setQuietTime('22:00', '23:00')
            ->setTimezone('America/Sao_Paulo');
        $client->register($device);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/device_tokens/123456789', $request->getPath());
        $this->assertEquals('PUT', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
        $this->assertEquals('application/json', $request->getHeader('Content-Type', true));
        $this->assertEquals('{"alias":"Luke Skywalker","tags":["republic","pilot"],"badge":1,"quiettime":{"start":"22:00","end":"23:00"},"tz":"America\/Sao_Paulo"}', $request->getBody()->__toString());
    }

    public function testIsDeactivatingAppleDevice()
    {
        $client = $this->getClientMock(new Response(200));
        $device = new AppleDevice('123456789');
        $client->deactivate($device);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/device_tokens/123456789', $request->getPath());
        $this->assertEquals('DELETE', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
    }

    public function testIsGettingAppleDeviceInfo()
    {
        $response = new Response(
            200,
            array('Content-Type' => 'application/json'),
            '{"device_token":"123456789","alias":"Luke Skywalker","last_registration":"2009-11-0620:41:06","created":"2009-11-0620:41:06","active":true,"tags":["tag1","tag2"]}'
        );
        $client = $this->getClientMock($response);
        $device = new AppleDevice('123456789');
        $output = $client->getInfo($device);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/device_tokens/123456789', $request->getPath());
        $this->assertEquals('GET', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
        $this->assertEquals('123456789', $output['device_token']);
        $this->assertEquals('Luke Skywalker', $output['alias']);
        $this->assertEquals('2009-11-0620:41:06', $output['last_registration']);
        $this->assertEquals('2009-11-0620:41:06', $output['created']);
        $this->assertEquals(true, $output['active']);
        $this->assertEquals(array('tag1', 'tag2'), $output['tags']);
    }
}