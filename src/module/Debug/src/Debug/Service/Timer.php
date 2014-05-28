<?php
namespace Debug\Service;

/**
 * Class Timer
 * @package Debug\Service
 */
class Timer
{
    /**
     * Start times.
     *
     * @var array
     */
    protected $start;

    /**
     * Defines if the time must be presented as float
     * @var boolean
     */
    protected $timeAsFloat;

    public function __construct($timeAsFloat)
    {
        $this->timeAsFloat = $timeAsFloat;
    }

    /**
     * Starts measuting the time
     *
     * @param $key
     */
    public function start($key)
    {
        $this->start[$key] = microtime($this->timeAsFloat);
    }

    /**
     * Stops measuring the time
     *
     * @param $key
     * @return mixed|null
     */
    public function stop($key)
    {
        if (!isset($this->start[$key])) {
            return null;
        }

        return microtime($this->timeAsFloat) - $this->start[$key];
    }
}
