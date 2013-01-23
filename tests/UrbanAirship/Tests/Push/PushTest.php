<?php

namespace UrbanAirship\Tests\Devices;

use UrbanAirship\Tests\UrbanAirshipTestCase;
use UrbanAirship\Device\BlackberryDevice;
use UrbanAirship\Device\AppleDevice;
use UrbanAirship\Device\AndroidDevice;
use UrbanAirship\Push\AndroidNotification;
use UrbanAirship\Push\AppleNotification;
use UrbanAirship\Push\BlackberryNotification;
use UrbanAirship\Push\BatchPush;
use Guzzle\Http\Message\Response;

class PushTest extends UrbanAirshipTestCase
{
    public function testIsPushingAndroidNotification()
    {
        $client = $this->getClientMock(new Response(200));
        $device = new AndroidDevice('123456789');
        $device->setAlias('Luke Skywalker')->addTag('republic')->addTag('pilot');

        $notification = new AndroidNotification();
        $notification->addDevice($device);
        $notification->setAlert('Hey dude!');
        $notification->setExtra(array('abc' => 123));
        $notification->addTag('sci-fi')->addTag('starwars');
        $notification->setAlias('Test');

        $client->push($notification);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/push', $request->getPath());
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
        $this->assertEquals('application/json', $request->getHeader('Content-Type', true));
        $this->assertEquals('{"apids":["123456789"],"aliases":"Test","tags":["sci-fi","starwars"],"android":{"alert":"Hey dude!","extra":{"abc":123}}}', $request->getBody()->__toString());
    }

    public function testIsPushingAndroidNotificationWithSchedule()
    {
        $client = $this->getClientMock(new Response(200));
        $device = new AndroidDevice('123456789');
        $device->setAlias('Luke Skywalker')->addTag('republic')->addTag('pilot');

        $notification = new AndroidNotification();
        $notification->addDevice($device);
        $notification->setAlert('Hey dude!');
        $schedule = new \DateTime('+1 week');
        $notification->scheduleFor($schedule);

        $client->push($notification);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/push', $request->getPath());
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
        $this->assertEquals('application/json', $request->getHeader('Content-Type', true));
        $response = sprintf('{"apids":["123456789"],"schedule_for":["%s"],"android":{"alert":"Hey dude!"}}', $schedule->format('c'));
        $this->assertEquals($response, $request->getBody()->__toString());
    }

    public function testIsPushingAndroidBroadcast()
    {
        $client = $this->getClientMock(new Response(200));

        $notification = new AndroidNotification();
        $notification->setAlert('Hey dude!');
        $notification->setExtra(array('abc' => 123));

        $client->push($notification);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/push/broadcast', $request->getPath());
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
        $this->assertEquals('application/json', $request->getHeader('Content-Type', true));
        $this->assertEquals('{"android":{"alert":"Hey dude!","extra":{"abc":123}}}', $request->getBody()->__toString());
    }

    public function testIsPushingAppleNotification()
    {
        $client = $this->getClientMock(new Response(200));
        $device = new AppleDevice('123456789');
        $device->setAlias('Luke Skywalker')->addTag('republic')->addTag('pilot');

        $notification = new AppleNotification();
        $notification->addDevice($device);
        $notification->setAlert('Hey dude!');
        $notification->setBadge(1);
        $notification->setSound('cat.caf');
        $notification->addTag('sci-fi')->addTag('starwars');
        $notification->setAlias('Test');

        $client->push($notification);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/push', $request->getPath());
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
        $this->assertEquals('application/json', $request->getHeader('Content-Type', true));
        $this->assertEquals('{"device_tokens":["123456789"],"aliases":"Test","tags":["sci-fi","starwars"],"aps":{"badge":1,"alert":"Hey dude!","sound":"cat.caf"}}', $request->getBody()->__toString());
    }

    public function testIsPushingAppleNotificationWithSchedule()
    {
        $client = $this->getClientMock(new Response(200));
        $device = new AppleDevice('123456789');
        $device->setAlias('Luke Skywalker')->addTag('republic')->addTag('pilot');

        $notification = new AppleNotification();
        $notification->addDevice($device);
        $notification->setAlert('Hey dude!');

        $schedule = new \DateTime('+1 week');
        $notification->scheduleFor($schedule);

        $client->push($notification);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/push', $request->getPath());
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
        $this->assertEquals('application/json', $request->getHeader('Content-Type', true));
        $response = sprintf('{"device_tokens":["123456789"],"schedule_for":["%s"],"aps":{"alert":"Hey dude!"}}', $schedule->format('c'));
        $this->assertEquals($response, $request->getBody()->__toString());
    }

    public function testIsPushingAppleBroadcast()
    {
        $client = $this->getClientMock(new Response(200));

        $notification = new AppleNotification();
        $notification->setAlert('Hey dude!');
        $notification->setSound('cat.caf');

        $client->push($notification);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/push/broadcast', $request->getPath());
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
        $this->assertEquals('application/json', $request->getHeader('Content-Type', true));
        $this->assertEquals('{"aps":{"alert":"Hey dude!","sound":"cat.caf"}}', $request->getBody()->__toString());
    }

    public function testIsPushingBlackberryNotification()
    {
        $client = $this->getClientMock(new Response(200));
        $device = new BlackberryDevice('123456789');
        $device->setAlias('Luke Skywalker')->addTag('republic')->addTag('pilot');

        $notification = new BlackberryNotification();
        $notification->addDevice($device);
        $notification->setBody('Hey dude!');
        $notification->setContentType('plain/text');
        $notification->addTag('sci-fi')->addTag('starwars');
        $notification->setAlias('Test');

        $client->push($notification);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/push', $request->getPath());
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
        $this->assertEquals('application/json', $request->getHeader('Content-Type', true));
        $this->assertEquals('{"device_pins":["123456789"],"aliases":"Test","tags":["sci-fi","starwars"],"blackberry":{"content-type":"plain\/text","body":"Hey dude!"}}', $request->getBody()->__toString());
    }

    public function testIsPushingBlackberryBroadcast()
    {
        $client = $this->getClientMock(new Response(200));

        $notification = new BlackberryNotification();
        $notification->setBody('Hey dude!');
        $notification->setContentType('plain/text');

        $client->push($notification);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/push/broadcast', $request->getPath());
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
        $this->assertEquals('application/json', $request->getHeader('Content-Type', true));
        $this->assertEquals('{"blackberry":{"content-type":"plain\/text","body":"Hey dude!"}}', $request->getBody()->__toString());
    }

    public function testIsBatchPushing()
    {
        $client = $this->getClientMock(new Response(200));
        $device = new AppleDevice('123456789');
        $device->setAlias('Luke Skywalker')->addTag('republic')->addTag('pilot');

        $notification = new AppleNotification();
        $notification->addDevice($device);
        $notification->setAlert('Hey dude!');
        $notification->setBadge(1);
        $notification->setSound('cat.caf');
        $notification->addTag('sci-fi')->addTag('starwars');
        $notification->setAlias('Test');

        $notification2 = new AppleNotification();
        $notification2->addDevice($device);
        $notification2->setAlert('Hey girl!');
        $notification2->setBadge(1);

        $batch = new BatchPush();
        $batch->addNotification($notification)->addNotification($notification2);

        $client->push($batch);

        $request = $this->getLastMockedRequest();
        $this->assertEquals('go.urbanairship.com', $request->getHost());
        $this->assertEquals('/api/push/batch', $request->getPath());
        $this->assertEquals('POST', $request->getMethod());
        $this->assertEquals('abc', $request->getUsername());
        $this->assertEquals('xyz', $request->getPassword());
        $this->assertEquals('application/json', $request->getHeader('Content-Type', true));
        $this->assertEquals('[{"device_tokens":["123456789"],"aliases":"Test","tags":["sci-fi","starwars"],"aps":{"badge":1,"alert":"Hey dude!","sound":"cat.caf"}},{"device_tokens":["123456789"],"aps":{"badge":1,"alert":"Hey girl!"}}]', $request->getBody()->__toString());
    }
}