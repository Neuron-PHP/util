<?php

namespace Neuron\Util;

/**
 * Class for performing stopwatch functions.
 * @package Neuron\Util
 */
class Timer implements ITimer
{
	private $_StartTime = 0;
	private $_StopTime  = 0;
	private $_MaxTime   = 0;

	private $_Laps = [];

	/**
	 * Timer constructor.
	 * @param int $iTime used for testing purposes.
	 */
	public function __construct( $iTime = 0 )
	{
		$this->_StopTime = $iTime;
	}

	public function setMaxTime( int $Max ) : Timer
	{
		$this->_MaxTime = $Max;

		return $this;
	}

	/**
	 * Start the timer.
	 */
	public function start()
	{
		$this->reset();
		$this->_StartTime = time();
	}

	/**
	 * Stop the timer.
	 */
	public function stop()
	{
		$this->_StopTime = time();
	}

	/**
	 * Reset all timer values.
	 */
	public function reset()
	{
		$this->_StartTime = 0;
		$this->_StopTime  = 0;
		$this->_MaxTime   = 0;
		$this->_Laps      = [];
	}

	public function lap( string $Name ) : int
	{
		$Current              = $this->getElapsed();
		$this->_Laps[ $Name ] = $Current;

		return $Current;
	}

	public function getLaps() : array
	{
		return $this->_Laps;
	}

	/**
	 * @return int Number of seconds elapsed .
	 */
	public function getElapsed()
	{
		if( $this->_StartTime && !$this->_StopTime )
		{
			return time() - $this->_StartTime;
		}

		return $this->_StopTime - $this->_StartTime;
	}

	public function execute( $Function )
	{
		$Result = $Function();

		return $Result;
	}
}
