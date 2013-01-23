<?php

namespace UrbanAirship\Push;

use UrbanAirship\Devices;
use UrbanAirship\Device\AndroidDevice;

class AndroidNotification extends AbstractNotification
{
    protected $path = 'api/push';
    protected $devices = array();
    protected $schedule = array();
    protected $alert;
    protected $extra;

    public function getDevices()
    {
        return $this->devices;
    }

    public function setDevices(Devices $devices)
    {
        $this->devices = $devices;
        return $this;
    }

    public function addDevice(AndroidDevice $device)
    {
        $this->devices[] = $device;
        return $this;
    }

    public function scheduleFor(\DateTime $date)
    {
        $this->schedule[] = $date;
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

    public function getExtra()
    {
        return $this->extra;
    }

    public function setExtra(array $extra)
    {
        $this->extra = $extra;
        return $this;
    }

    public function getPayload()
    {
        $payload = array(
            'apids' => array_map(function($device) { return $device->getId(); }, $this->devices),
            'aliases' => $this->alias,
            'tags' => $this->tags,
            'schedule_for' => array_map(function($date) { return $date->format('c'); }, $this->schedule),
            'android' => array(
                'alert' => $this->alert,
                'extra' => $this->extra,
            ),
        );

        $payload = array_filter($payload);
        $payload['android'] = array_filter($payload['android']);
        return json_encode($payload);
    }
}