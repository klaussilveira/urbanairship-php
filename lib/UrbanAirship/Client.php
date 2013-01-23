<?php

namespace UrbanAirship;

use UrbanAirship\Device\DeviceInterface;
use UrbanAirship\Device\RegisterableDeviceInterface;
use UrbanAirship\Device\AppleDevice;
use UrbanAirship\Device\BlackberryDevice;
use UrbanAirship\Push\PushableInterface;
use Guzzle\Http\Client as HttpClient;

class Client
{
    protected $appKey;
    protected $appSecret;
    protected $client;

    public function __construct($appKey, $appSecret, $baseUrl = 'https://go.urbanairship.com')
    {
        $this->appKey = $appKey;
        $this->appSecret = $appSecret;
        $this->client = new HttpClient($baseUrl);
    }

    public function getAppKey()
    {
        return $this->appKey;
    }

    public function setAppKey($appKey)
    {
        $this->appKey = $appKey;
        return $this;
    }

    public function getAppSecret()
    {
        return $this->appSecret;
    }

    public function setAppSecret($appSecret)
    {
        $this->appSecret = $appSecret;
        return $this;
    }

    public function getClient()
    {
        return $this->client;
    }

    public function setClient($client)
    {
        $this->client = $client;
        return $this;
    }

    public function getDeviceList()
    {
        $route = '/api/device_tokens/';
        $allDevices = $this->getDevicePage($route);

        $devices = new Devices;
        $devices->setDevices($allDevices['device_tokens']);
        $devices->setTotal($allDevices['device_tokens_count']);
        $devices->setActive($allDevices['active_device_tokens_count']);
        return $devices;
    }

    protected function getDevicePage($url)
    {
        $data = $this->get($url)->send()->json();
        $devices = array();
        $devices['device_tokens'] = array();

        foreach ($data['device_tokens'] as $item) {
            $device = new AppleDevice($item['device_token']);
            $device->setActive($item['active']);
            $device->setTags($item['tags']);
            $device->setAlias($item['alias']);
            $devices['device_tokens'][] = $device;
        }

        $devices['device_tokens_count'] = $data['device_tokens_count'];
        $devices['active_device_tokens_count'] = $data['active_device_tokens_count'];

        if (isset($data['next_page'])) {
            $output = $this->getDevicePage($data['next_page']);
            $devices['device_tokens'] = array_merge($devices['device_tokens'], $output['device_tokens']);
        }

        return $devices;
    }

    public function getDeviceCount()
    {
        $route = '/api/device_tokens/count/';
        $data = $this->get($route)->send()->json();

        $devices = new Devices;
        $devices->setTotal($data['device_tokens_count']);
        $devices->setActive($data['active_device_tokens_count']);
        return $devices;
    }

    public function register(RegisterableDeviceInterface $device)
    {
        $route = sprintf('/%s/%s/', $device->getPath(), $device->getId());
        $request = $this->put($route);

        if ($device->hasPayload()) {
            $request->setBody($device->getPayload())->setHeader('Content-Type', 'application/json');
        }

        $request->send();
    }

    public function deactivate(RegisterableDeviceInterface $device)
    {
        $route = sprintf('/%s/%s/', $device->getPath(), $device->getId());
        $this->delete($route)->send();
    }

    public function getInfo(DeviceInterface $device)
    {
        $route = sprintf('/%s/%s/', $device->getPath(), $device->getId());
        return $this->get($route)->send()->json();
    }

    public function push(PushableInterface $notification)
    {
        $route = sprintf('/%s/', $notification->getPath());
        $this
            ->post($route)
            ->setBody($notification->getPayload())
            ->setHeader('Content-Type', 'application/json')
            ->send();
        // TODO: parse response in case of scheduled notifications
    }

    protected function get($url)
    {
        return $this->client->get($url)->setAuth($this->appKey, $this->appSecret);
    }

    protected function post($url)
    {
        return $this->client->post($url)->setAuth($this->appKey, $this->appSecret);
    }

    protected function put($url)
    {
        return $this->client->put($url)->setAuth($this->appKey, $this->appSecret);
    }

    protected function delete($url)
    {
        return $this->client->delete($url)->setAuth($this->appKey, $this->appSecret);
    }
}
