<?php

namespace Tests\Util;

use Neuron\Util\File\Temporary;
use PHPUnit\Framework\TestCase;

class TemporaryTest extends TestCase
{
	public function testGetFile()
	{
		$name = Temporary::getFile();

		$this->assertFalse( file_exists( $name ) );
	}
}
