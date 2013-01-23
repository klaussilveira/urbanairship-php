<?php

namespace UrbanAirship;

class Devices implements \Iterator, \Countable
{
    protected $position = 0;
    protected $total = 0;
    protected $active = 0;
    protected $devices = array();

    public function __construct()
    {
        $this->position = 0;
    }

    public function count()
    {
        return count($this->devices);
    }

    public function setDevices($devices)
    {
        $this->devices = $devices;
    }

    public function getTotal()
    {
        return $this->total;
    }

    public function setTotal($total)
    {
        $this->total = $total;
        return $this;
    }

    public function getActive()
    {
        return $this->active;
    }

    public function setActive($active)
    {
        $this->active = $active;
        return $this;
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->devices[$this->position];
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
        return isset($this->devices[$this->position]);
    }
}