<?php

namespace UrbanAirship\Push;

class BatchPush implements PushableInterface, \Iterator, \Countable
{
    protected $path = 'api/push/batch';
    protected $position = 0;
    protected $notifications = array();

    public function __construct()
    {
        $this->position = 0;
    }

    public function count()
    {
        return count($this->notifications);
    }

    public function getPath()
    {
        return $this->path;
    }

    public function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function setNotifications($notifications)
    {
        $this->notifications = $notifications;
    }

    public function addNotification(NotificationInterface $notification)
    {
        $this->notifications[] = $notification;
        return $this;
    }

    public function getPayload()
    {
        foreach ($this->notifications as $notification) {
            $payload[] = json_decode($notification->getPayload(), true);
        }

        return json_encode($payload);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->notifications[$this->position];
    }

    public function key()
    {
        return $this->position;
    }

    public function next()
    {
        ++$this->position;
    }

    public function valid()
    {
        return isset($this->notifications[$this->position]);
    }
}
