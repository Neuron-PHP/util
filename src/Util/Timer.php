<?php

namespace Neuron\Util;

/**
 * Class for performing stopwatch functions.
 */
class Timer implements ITimer
{
	private $_startTime = 0;
	private $_stopTime  = 0;
	private $_maxTime   = 0;

	private $_laps = [];

	/**
	 * Timer constructor.
	 * @param int $time used for testing purposes.
	 */
	public function __construct( $time = 0 )
	{
		$this->_stopTime = $time;
	}

	public function setMaxTime( int $max ) : Timer
	{
		$this->_maxTime = $max;

		return $this;
	}

	/**
	 * Start the timer.
	 */
	public function start()
	{
		$this->reset();
		$this->_startTime = time();
	}

	/**
	 * Stop the timer.
	 */
	public function stop()
	{
		$this->_stopTime = time();
	}

	/**
	 * Reset all timer values.
	 */
	public function reset()
	{
		$this->_startTime = 0;
		$this->_stopTime  = 0;
		$this->_maxTime   = 0;
		$this->_laps      = [];
	}

	public function lap( string $name ) : int
	{
		$current              = $this->getElapsed();
		$this->_laps[ $name ] = $current;

		return $current;
	}

	public function getLaps() : array
	{
		return $this->_laps;
	}

	/**
	 * @return int Number of seconds elapsed .
	 */
	public function getElapsed()
	{
		if( $this->_startTime && !$this->_stopTime )
		{
			return time() - $this->_startTime;
		}

		return $this->_stopTime - $this->_startTime;
	}

	public function execute( $function )
	{
		$result = $function();

		return $result;
	}
}
