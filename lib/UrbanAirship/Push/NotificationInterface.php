<?php

namespace UrbanAirship\Push;

interface NotificationInterface
{
    public function getAlias();
    public function setAlias($alias);
    public function getTags();
    public function setTags($tags);
    public function addTag($tag);
}
