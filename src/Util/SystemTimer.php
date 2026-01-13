<?php

namespace Neuron\Util;

use Neuron\Patterns\Singleton\Memory;

/**
 * Singleton based timer.
 */
class SystemTimer extends Memory
{
	private $_timer;

	public function init()
	{
		if( !$this->_timer )
		{
			$this->_timer = new Timer();
			$this->serialize();
		}
	}

	public static function start()
	{
		$timer = self::getInstance();
		$timer->init();
		$timer->_timer->start();
		$timer->serialize();
	}

	public static function stop()
	{
		$timer = self::getInstance();
		$timer->init();
		$timer->_timer->stop();
		$timer->serialize();
	}

	public static function reset()
	{
		$timer = self::getInstance();
		$timer->init();
		$timer->_timer->reset();
		$timer->serialize();
	}

	public static function lap( string $name ): int
	{
		$timer = self::getInstance();
		$timer->init();
		$lap   = $timer->_timer->lap( $name );

		$timer->serialize();
		return $lap;
	}

	public static function getLaps(): array
	{
		$timer = self::getInstance();
		$timer->init();
		return $timer->_timer->getLaps();
	}

	public static function getElapsed()
	{
		$timer = self::getInstance();
		$timer->init();
		return $timer->_timer->getElapsed();
	}
}
