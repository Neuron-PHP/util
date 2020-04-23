<?php

use Neuron\Util\WebHook;
use PHPUnit\Framework\TestCase;

class WebHookTest extends TestCase
{
	public function testGet200()
	{
		$Webhook = new WebHook();

		$Response = $Webhook->get(
			'http://sitestagingarea.com/guarder/guarder_transponder.php',
			[
				'apikey' => '1234123412341234'
			]
		);

		$this->assertEquals(
			200,
			$Response->getHttpCode()
		);

		$Data = json_decode( $Response->getData(), true );

		$this->assertArrayHasKey(
			'memory',
			$Data
		);
	}

	public function testGet401()
	{
		$Webhook = new WebHook();

		$Response = $Webhook->get(
			'http://sitestagingarea.com/guarder/guarder_transponder.php'
		);

		$this->assertEquals(
			401,
			$Response->getHttpCode()
		);
	}

}
