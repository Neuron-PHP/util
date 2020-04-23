<?php

namespace Neuron\Util;

use Neuron\Patterns\Singleton\Memory;

class SystemTimer extends Memory
{
	private $_Timer;

	public function init()
	{
		if( !$this->_Timer )
		{
			$this->_Timer = new Timer();
			$this->serialize();
		}
	}

	public static function start()
	{
		$Timer = self::getInstance();
		$Timer->init();
		$Timer->_Timer->start();
		$Timer->serialize();
	}

	public static function stop()
	{
		$Timer = self::getInstance();
		$Timer->init();
		$Timer->_Timer->stop();
		$Timer->serialize();
	}

	public static function reset()
	{
		$Timer = self::getInstance();
		$Timer->init();
		$Timer->_Timer->reset();
		$Timer->serialize();
	}

	public static function lap( string $Name ): int
	{
		$Timer = self::getInstance();
		$Timer->init();
		$Lap   = $Timer->_Timer->lap( $Name );

		$Timer->serialize();
		return $Lap;
	}

	public static function getLaps(): array
	{
		$Timer = self::getInstance();
		$Timer->init();
		return $Timer->_Timer->getLaps();
	}

	public static function getElapsed()
	{
		$Timer = self::getInstance();
		$Timer->init();
		return $Timer->_Timer->getElapsed();
	}
}
