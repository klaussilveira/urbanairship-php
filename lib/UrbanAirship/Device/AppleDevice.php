<?php

namespace UrbanAirship\Device;

class AppleDevice extends AbstractDevice implements RegisterableDeviceInterface
{
    protected $path = 'api/device_tokens';
    protected $badge;
    protected $quietTime;
    protected $timezone;

    public function getBadge()
    {
        return $this->badge;
    }

    public function setBadge($badge)
    {
        $this->badge = $badge;
        return $this;
    }

    public function getQuietTime()
    {
        return $this->quietTime;
    }

    public function setQuietTime($start, $end)
    {
        $this->quietTime = array('start' => $start, 'end' => $end);
        return $this;
    }

    public function getTimezone()
    {
        return $this->timezone;
    }

    public function setTimezone($timezone)
    {
        $this->timezone = $timezone;
        return $this;
    }

    public function createPayload()
    {
        return array(
            'alias' => $this->alias,
            'tags' => $this->tags,
            'badge' => $this->badge,
            'quiettime' => $this->quietTime,
            'tz' => $this->timezone,
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