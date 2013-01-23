<?php

namespace UrbanAirship\Device;

interface RegisterableDeviceInterface
{
    public function createPayload();
    public function hasPayload();
    public function getPayload();
}
