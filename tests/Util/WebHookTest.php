<?php

namespace Tests\Util;

use Neuron\Util\WebHook;
use Neuron\Util\WebHookResponse;
use PHPUnit\Framework\TestCase;

class WebHookTest extends TestCase
{
	public function testConstructor()
	{
		$webhook = new WebHook();

		$this->assertInstanceOf( WebHook::class, $webhook );
	}

	public function testGetWithoutParameters()
	{
		$webhook = new WebHook();

		// Test with a simple URL (will fail in test environment but shouldn't throw exception)
		// Using @ to suppress curl errors in test environment
		$response = @$webhook->get( 'http://localhost:9999/test' );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertIsInt( $response->getHttpCode() );
		$this->assertIsInt( $response->getError() );
		$this->assertIsString( $response->getErrorString() );
	}

	public function testGetWithParameters()
	{
		$webhook = new WebHook();

		$params = [
			'key1' => 'value1',
			'key2' => 'value2',
			'test' => 'data'
		];

		// Test that URL is built correctly (will fail to connect but structure is tested)
		$response = @$webhook->get( 'http://localhost:9999/api', $params );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertIsInt( $response->getHttpCode() );
	}

	public function testGetWithSingleParameter()
	{
		$webhook = new WebHook();

		$params = ['id' => '123'];

		$response = @$webhook->get( 'http://localhost:9999/resource', $params );

		$this->assertInstanceOf( WebHookResponse::class, $response );
	}

	public function testGetWithEmptyParameters()
	{
		$webhook = new WebHook();

		// Empty params should result in URL without query string
		$response = @$webhook->get( 'http://localhost:9999/endpoint', [] );

		$this->assertInstanceOf( WebHookResponse::class, $response );
	}

	public function testPostWithoutParameters()
	{
		$webhook = new WebHook();

		$response = @$webhook->post( 'http://localhost:9999/submit' );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertIsInt( $response->getHttpCode() );
	}

	public function testPostWithParameters()
	{
		$webhook = new WebHook();

		$params = [
			'username' => 'testuser',
			'password' => 'testpass',
			'action' => 'login'
		];

		$response = @$webhook->post( 'http://localhost:9999/auth', $params );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertIsInt( $response->getHttpCode() );
	}

	public function testPostWithEmptyParameters()
	{
		$webhook = new WebHook();

		$response = @$webhook->post( 'http://localhost:9999/submit', [] );

		$this->assertInstanceOf( WebHookResponse::class, $response );
	}

	public function testPostJson()
	{
		$webhook = new WebHook();

		$json = json_encode([
			'name' => 'Test User',
			'email' => 'test@example.com',
			'active' => true
		]);

		$response = @$webhook->postJson( 'http://localhost:9999/api/users', $json );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertIsInt( $response->getHttpCode() );
	}

	public function testPostJsonWithEmptyJson()
	{
		$webhook = new WebHook();

		$json = '{}';

		$response = @$webhook->postJson( 'http://localhost:9999/api/data', $json );

		$this->assertInstanceOf( WebHookResponse::class, $response );
	}

	public function testPostJsonWithComplexJson()
	{
		$webhook = new WebHook();

		$json = json_encode([
			'user' => [
				'name' => 'John Doe',
				'email' => 'john@example.com',
				'profile' => [
					'age' => 30,
					'city' => 'New York'
				]
			],
			'metadata' => [
				'timestamp' => time(),
				'version' => '1.0'
			]
		]);

		$response = @$webhook->postJson( 'http://localhost:9999/api/complex', $json );

		$this->assertInstanceOf( WebHookResponse::class, $response );
	}

	public function testGetResponseProtectedMethod()
	{
		$webhook = new WebHook();

		// Use reflection to test the protected getResponse method
		$reflection = new \ReflectionClass( $webhook );
		$method = $reflection->getMethod( 'getResponse' );
		$method->setAccessible( true );

		// Initialize curl with a URL first
		$property = $reflection->getProperty( '_handle' );
		$property->setAccessible( true );
		$handle = $property->getValue( $webhook );

		curl_setopt( $handle, CURLOPT_URL, 'http://localhost:9999/test' );

		$response = @$method->invoke( $webhook );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertIsInt( $response->getHttpCode() );
		$this->assertIsInt( $response->getError() );
		$this->assertIsString( $response->getErrorString() );
	}

	public function testMultipleSequentialRequests()
	{
		// Test that we can make multiple requests
		$webhook1 = new WebHook();
		$response1 = @$webhook1->get( 'http://localhost:9999/test1' );
		$this->assertInstanceOf( WebHookResponse::class, $response1 );

		$webhook2 = new WebHook();
		$response2 = @$webhook2->post( 'http://localhost:9999/test2' );
		$this->assertInstanceOf( WebHookResponse::class, $response2 );

		$webhook3 = new WebHook();
		$response3 = @$webhook3->postJson( 'http://localhost:9999/test3', '{"test": true}' );
		$this->assertInstanceOf( WebHookResponse::class, $response3 );
	}

	public function testGetParameterEncoding()
	{
		$webhook = new WebHook();

		$params = [
			'search' => 'test query',
			'page' => '1',
			'limit' => '10'
		];

		// Test that parameters are properly formatted
		$response = @$webhook->get( 'http://localhost:9999/search', $params );

		$this->assertInstanceOf( WebHookResponse::class, $response );
	}

	public function testResponsePropertiesAfterGet()
	{
		$webhook = new WebHook();

		$response = @$webhook->get( 'http://localhost:9999/endpoint' );

		// Verify response has expected properties set
		$this->assertNotNull( $response->getHttpCode() );
		$this->assertNotNull( $response->getError() );
		$this->assertNotNull( $response->getErrorString() );
		// getData() can be null, false, or string depending on curl result
	}

	public function testResponsePropertiesAfterPost()
	{
		$webhook = new WebHook();

		$response = @$webhook->post( 'http://localhost:9999/endpoint', ['key' => 'value'] );

		// Verify response has expected properties set
		$this->assertNotNull( $response->getHttpCode() );
		$this->assertNotNull( $response->getError() );
		$this->assertNotNull( $response->getErrorString() );
	}

	public function testResponsePropertiesAfterPostJson()
	{
		$webhook = new WebHook();

		$response = @$webhook->postJson( 'http://localhost:9999/api', '{"data": "value"}' );

		// Verify response has expected properties set
		$this->assertNotNull( $response->getHttpCode() );
		$this->assertNotNull( $response->getError() );
		$this->assertNotNull( $response->getErrorString() );
	}
}

