<?php

namespace UrbanAirship\Push;

use UrbanAirship\Devices;
use UrbanAirship\Device\BlackberryDevice;

class BlackberryNotification extends AbstractNotification
{
    protected $path = 'api/push';
    protected $devices = array();
    protected $contentType;
    protected $body;

    public function getDevices()
    {
        return $this->devices;
    }

    public function setDevices(Devices $devices)
    {
        $this->devices = $devices;
        return $this;
    }

    public function addDevice(BlackberryDevice $device)
    {
        $this->devices[] = $device;
        return $this;
    }

    public function scheduleFor(\DateTime $date)
    {
        $this->schedule[] = $date;
        return $this;
    }

    public function getContentType()
    {
        return $this->contentType;
    }

    public function setContentType($contentType)
    {
        $this->contentType = $contentType;
        return $this;
    }

    public function getBody()
    {
        return $this->body;
    }

    public function setBody($body)
    {
        $this->body = $body;
        return $this;
    }

    public function getPayload()
    {
        $payload = array(
            'device_pins' => array_map(function ($device) { return $device->getId(); }, $this->devices),
            'aliases' => $this->alias,
            'tags' => $this->tags,
            'blackberry' => array(
                'content-type' => $this->contentType,
                'body' => $this->body,
            ),
        );

        $payload = array_filter($payload);
        $payload['blackberry'] = array_filter($payload['blackberry']);
        return json_encode($payload);
    }
}
