<?php

class TemporaryTest extends PHPUnit\Framework\TestCase
{
	public function testGetFile()
	{
		$name = \Neuron\Util\File\Temporary::getFile();

		$this->assertFalse( file_exists( $name ) );
	}
}
