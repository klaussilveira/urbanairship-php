<?php

namespace UrbanAirship\Push;

interface PushableInterface
{
    public function getPath();
    public function setPath($path);
    public function getPayload();
}
