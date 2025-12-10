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

	public function testSetMaxTime()
	{
		$timer = new \Neuron\Util\Timer();

		$result = $timer->setMaxTime( 60 );

		$this->assertInstanceOf( \Neuron\Util\Timer::class, $result );
	}

	public function testSetMaxTimeWithChaining()
	{
		$timer = new \Neuron\Util\Timer();

		// Test fluent interface
		$result = $timer->setMaxTime( 120 )->start();

		$this->assertNull( $result );
	}

	public function testStop()
	{
		$timer = new \Neuron\Util\Timer();

		$timer->start();
		sleep( 1 );
		$timer->stop();

		$elapsed1 = $timer->getElapsed();

		// Wait a bit more
		sleep( 1 );

		$elapsed2 = $timer->getElapsed();

		// Elapsed should not increase after stop
		$this->assertEquals( $elapsed1, $elapsed2 );
	}

	public function testExecute()
	{
		$timer = new \Neuron\Util\Timer();

		$result = $timer->execute( function() {
			return 'test result';
		} );

		$this->assertEquals( 'test result', $result );
	}

	public function testExecuteWithReturnValue()
	{
		$timer = new \Neuron\Util\Timer();

		$result = $timer->execute( function() {
			return 42;
		} );

		$this->assertEquals( 42, $result );
	}

	public function testGetElapsedWhileRunning()
	{
		$timer = new \Neuron\Util\Timer();

		$timer->start();
		sleep( 1 );

		// Timer is still running (not stopped)
		$elapsed = $timer->getElapsed();

		$this->assertGreaterThan( 0, $elapsed );
	}
}
