<?php

use Neuron\Util\SystemTimer;

class SystemTimerTest extends PHPUnit\Framework\TestCase
{
	public function testGetLaps()
	{
		SystemTimer::start();
		SystemTimer::lap( 'one' );
		SystemTimer::lap( 'two' );

		$this->assertIsArray(
			SystemTimer::getLaps()
		);
	}

	public function testStop()
	{
		SystemTimer::start();
		SystemTimer::stop();
		sleep( 1 );
		$this->assertGreaterThanOrEqual(
			0,
			SystemTimer::getElapsed()
		);
	}

	public function testStart()
	{
		SystemTimer::start();
		sleep( 2 );
		$this->assertGreaterThanOrEqual(
			1,
			SystemTimer::getElapsed()
		);
	}

	public function testReset()
	{
		SystemTimer::start();
		sleep( 1 );
		SystemTimer::stop();
		SystemTimer::reset();
		$this->assertGreaterThanOrEqual(
			0,
			SystemTimer::getElapsed()
		);
	}
}
