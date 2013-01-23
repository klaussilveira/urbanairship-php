<?php

namespace UrbanAirship\Device;

class BlackberryDevice extends AbstractDevice implements RegisterableDeviceInterface
{
    protected $path = 'api/device_pins';

    public function createPayload()
    {
        return array(
            'alias' => $this->alias,
            'tags' => $this->tags,
        );
    }

    public function hasPayload()
    {
        $payload = $this->createPayload();

        foreach ($payload as $item) {
            if (!empty($item)) {
                return true;
            }
        }

        return false;
    }

    public function getPayload()
    {
        return json_encode($this->createPayload());
    }
}