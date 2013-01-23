<?php

namespace UrbanAirship\Push;

abstract class AbstractNotification implements NotificationInterface, PushableInterface
{
    protected $path;
    protected $alias;
    protected $tags;

    public function getPath()
    {
        if (empty($this->devices) && empty($this->alias) && empty($this->tags)) {
            return 'api/push/broadcast';
        }

        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function getAlias()
    {
        return $this->alias;
    }

    public function setAlias($alias)
    {
        $this->alias = $alias;
        return $this;
    }

    public function getTags()
    {
        return $this->tags;
    }

    public function setTags($tags)
    {
        $this->tags = $tags;
        return $this;
    }

    public function addTag($tag)
    {
        $this->tags[] = $tag;
        return $this;
    }
}
