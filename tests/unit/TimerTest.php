<?php

/**
 * Class TimerTest
 */
class TimerTest extends PHPUnit\Framework\TestCase
{
	public function testTimer()
	{
		$offset = 5;

		$timer = new Neuron\Util\Timer( $offset );

		$elapsed = $timer->getElapsed();

		$this->assertEquals( $elapsed, $offset );
	}

	public function testReset()
	{
		$timer = new \Neuron\Util\Timer();

		$timer->start();

		sleep( 2 );

		$timer->reset();

		$this->assertEquals(
			$timer->getElapsed(),
			0
		);
	}

	public function testLaps()
	{
		$timer = new \Neuron\Util\Timer();

		$timer->start();

		sleep( 1 );
		$this->assertTrue( $timer->lap( 'one' ) > 0 );
		sleep( 1 );
		$this->assertTrue( $timer->lap( 'two' ) > 0 );

		$laps = $timer->getLaps();

		$this->assertIsArray( $laps );

		$this->assertArrayHasKey( 'one', $laps );
		$this->assertArrayHasKey( 'two', $laps );
	}
}
