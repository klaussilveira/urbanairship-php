<?php

namespace UrbanAirship\Push;

use UrbanAirship\Devices;
use UrbanAirship\Device\AppleDevice;

class AppleNotification extends AbstractNotification
{
    protected $path = 'api/push';
    protected $devices = array();
    protected $exclude = array();
    protected $schedule = array();
    protected $badge;
    protected $alert;
    protected $sound;

    public function getDevices()
    {
        return $this->devices;
    }

    public function setDevices(Devices $devices)
    {
        $this->devices = $devices;
        return $this;
    }

    public function addDevice(AppleDevice $device)
    {
        $this->devices[] = $device;
        return $this;
    }

    public function excludeDevice(AppleDevice $device)
    {
        $this->exclude[] = $device;
        return $this;
    }

    public function scheduleFor(\DateTime $date)
    {
        $this->schedule[] = $date;
        return $this;
    }

    public function getBadge()
    {
        return $this->badge;
    }

    public function setBadge($badge)
    {
        $this->badge = $badge;
        return $this;
    }

    public function getAlert()
    {
        return $this->alert;
    }

    public function setAlert($alert)
    {
        $this->alert = $alert;
        return $this;
    }

    public function getSound()
    {
        return $this->sound;
    }

    public function setSound($sound)
    {
        $this->sound = $sound;
        return $this;
    }

    public function getPayload()
    {
        $payload = array(
            'device_tokens' => array_map(function ($device) { return $device->getId(); }, $this->devices),
            'aliases' => $this->alias,
            'tags' => $this->tags,
            'schedule_for' => array_map(function ($date) { return $date->format('c'); }, $this->schedule),
            'exclude_tokens' => array_map(function ($device) { return $device->getId(); }, $this->exclude),
            'aps' => array(
                'badge' => $this->badge,
                'alert' => $this->alert,
                'sound' => $this->sound,
            ),
        );

        $payload = array_filter($payload);
        $payload['aps'] = array_filter($payload['aps']);
        return json_encode($payload);
    }
}
