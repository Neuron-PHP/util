<?php

namespace Tests\Util;

use Neuron\Util\WebHookResponse;
use PHPUnit\Framework\TestCase;

class WebHookResponseTest extends TestCase
{
	public function testConstructor()
	{
		$response = new WebHookResponse();

		$this->assertInstanceOf( WebHookResponse::class, $response );
	}

	public function testSetAndGetHttpCode()
	{
		$response = new WebHookResponse();

		$result = $response->setHttpCode( 200 );

		$this->assertInstanceOf( WebHookResponse::class, $result );
		$this->assertEquals( 200, $response->getHttpCode() );
	}

	public function testSetAndGetHttpCodeVariousCodes()
	{
		$response = new WebHookResponse();

		$response->setHttpCode( 404 );
		$this->assertEquals( 404, $response->getHttpCode() );

		$response->setHttpCode( 500 );
		$this->assertEquals( 500, $response->getHttpCode() );

		$response->setHttpCode( 201 );
		$this->assertEquals( 201, $response->getHttpCode() );
	}

	public function testSetAndGetData()
	{
		$response = new WebHookResponse();

		$testData = '{"status": "success", "message": "OK"}';
		$result = $response->setData( $testData );

		$this->assertInstanceOf( WebHookResponse::class, $result );
		$this->assertEquals( $testData, $response->getData() );
	}

	public function testSetAndGetDataWithDifferentTypes()
	{
		$response = new WebHookResponse();

		// Test with string
		$response->setData( 'string data' );
		$this->assertEquals( 'string data', $response->getData() );

		// Test with array
		$arrayData = ['key' => 'value'];
		$response->setData( $arrayData );
		$this->assertEquals( $arrayData, $response->getData() );

		// Test with false (curl_exec can return false)
		$response->setData( false );
		$this->assertFalse( $response->getData() );
	}

	public function testSetAndGetError()
	{
		$response = new WebHookResponse();

		$result = $response->setError( 0 );

		$this->assertInstanceOf( WebHookResponse::class, $result );
		$this->assertEquals( 0, $response->getError() );
	}

	public function testSetAndGetErrorWithVariousErrorCodes()
	{
		$response = new WebHookResponse();

		// CURLE_OK
		$response->setError( 0 );
		$this->assertEquals( 0, $response->getError() );

		// CURLE_COULDNT_RESOLVE_HOST
		$response->setError( 6 );
		$this->assertEquals( 6, $response->getError() );

		// CURLE_OPERATION_TIMEDOUT
		$response->setError( 28 );
		$this->assertEquals( 28, $response->getError() );
	}

	public function testSetAndGetErrorString()
	{
		$response = new WebHookResponse();

		$errorString = 'Connection timeout';
		$result = $response->setErrorString( $errorString );

		$this->assertInstanceOf( WebHookResponse::class, $result );
		$this->assertEquals( $errorString, $response->getErrorString() );
	}

	public function testSetAndGetErrorStringWithEmptyString()
	{
		$response = new WebHookResponse();

		$response->setErrorString( '' );
		$this->assertEquals( '', $response->getErrorString() );
	}

	public function testFluentInterface()
	{
		$response = new WebHookResponse();

		// Test method chaining
		$result = $response
			->setHttpCode( 200 )
			->setData( '{"success": true}' )
			->setError( 0 )
			->setErrorString( '' );

		$this->assertInstanceOf( WebHookResponse::class, $result );
		$this->assertEquals( 200, $response->getHttpCode() );
		$this->assertEquals( '{"success": true}', $response->getData() );
		$this->assertEquals( 0, $response->getError() );
		$this->assertEquals( '', $response->getErrorString() );
	}

	public function testCompleteResponseConfiguration()
	{
		$response = new WebHookResponse();

		$response->setHttpCode( 201 );
		$response->setData( '{"id": 123, "created": true}' );
		$response->setError( 0 );
		$response->setErrorString( '' );

		$this->assertEquals( 201, $response->getHttpCode() );
		$this->assertEquals( '{"id": 123, "created": true}', $response->getData() );
		$this->assertEquals( 0, $response->getError() );
		$this->assertEquals( '', $response->getErrorString() );
	}

	public function testErrorResponseConfiguration()
	{
		$response = new WebHookResponse();

		$response->setHttpCode( 0 );
		$response->setData( false );
		$response->setError( 28 );
		$response->setErrorString( 'Operation timed out after 10000 milliseconds' );

		$this->assertEquals( 0, $response->getHttpCode() );
		$this->assertFalse( $response->getData() );
		$this->assertEquals( 28, $response->getError() );
		$this->assertEquals( 'Operation timed out after 10000 milliseconds', $response->getErrorString() );
	}
}

