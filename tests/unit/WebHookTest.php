<?php

use Neuron\Util\WebHook;
use PHPUnit\Framework\TestCase;

class WebHookTest extends TestCase
{
	/**
	public function testGet200()
	{
		$webhook = new WebHook();

		$response = $webhook->get(
			'http://sitestagingarea.com/guarder/guarder_transponder.php',
			[
				'apikey' => '1234123412341234'
			]
		);

		$this->assertEquals(
			200,
			$response->getHttpCode()
		);

		$data = json_decode( $response->getData(), true );

		$this->assertArrayHasKey(
			'memory',
			$data
		);
	}
	 */

	/*
	public function testGet401()
	{
		$webhook = new WebHook();

		$response = $webhook->get(
			'http://sitestagingarea.com/guarder/guarder_transponder.php'
		);

		$this->assertEquals(
			401,
			$response->getHttpCode()
		);
	}
	*/
}
