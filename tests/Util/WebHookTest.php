<?php

namespace Tests\Util;

use Neuron\Core\System\MemoryHttpClient;
use Neuron\Core\System\HttpResponse;
use Neuron\Util\WebHook;
use Neuron\Util\WebHookResponse;
use PHPUnit\Framework\TestCase;

class WebHookTest extends TestCase
{
	private MemoryHttpClient $httpClient;
	private WebHook $webhook;

	protected function setUp(): void
	{
		$this->httpClient = new MemoryHttpClient();
		$this->webhook = new WebHook( $this->httpClient );
	}

	public function testConstructor()
	{
		$webhook = new WebHook();
		$this->assertInstanceOf( WebHook::class, $webhook );
	}

	public function testConstructorWithHttpClient()
	{
		$client = new MemoryHttpClient();
		$webhook = new WebHook( $client );
		$this->assertInstanceOf( WebHook::class, $webhook );
	}

	public function testGetWithoutParameters()
	{
		// Program a successful response for any URL
		$this->httpClient->addResponse(
			'*',
			new HttpResponse( 200, '{"status":"success"}', [] )
		);

		$response = $this->webhook->get( 'https://api.example.com/test' );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertEquals( 200, $response->getHttpCode() );
		$this->assertEquals( '{"status":"success"}', $response->getData() );
		$this->assertEquals( 0, $response->getError() );
		$this->assertEquals( '', $response->getErrorString() );
	}

	public function testGetWithParameters()
	{
		$this->httpClient->addResponse(
			'*',
			new HttpResponse( 200, '{"data":"found"}', [] )
		);

		$params = [
			'key1' => 'value1',
			'key2' => 'value2',
			'test' => 'data'
		];

		$response = $this->webhook->get( 'https://api.example.com/api', $params );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertEquals( 200, $response->getHttpCode() );
		$this->assertEquals( '{"data":"found"}', $response->getData() );

		// Verify the request was made correctly
		$requests = $this->httpClient->getRequests();
		$this->assertCount( 1, $requests );
		$this->assertEquals( 'GET', $requests[0]['method'] );
		// URL will have query string appended
		$this->assertStringContainsString( 'https://api.example.com/api', $requests[0]['url'] );
	}

	public function testGetWithSingleParameter()
	{
		$this->httpClient->addResponse(
			'*',
			new HttpResponse( 200, '{"id":"123","found":true}', [] )
		);

		$params = ['id' => '123'];
		$response = $this->webhook->get( 'https://api.example.com/resource', $params );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertEquals( 200, $response->getHttpCode() );
	}

	public function testGetWithEmptyParameters()
	{
		$this->httpClient->addResponse(
			'*',
			new HttpResponse( 200, '{"message":"ok"}', [] )
		);

		// Empty params should result in URL without query string
		$response = $this->webhook->get( 'https://api.example.com/endpoint', [] );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertEquals( 200, $response->getHttpCode() );
	}

	public function testGetWith404Response()
	{
		$this->httpClient->addResponse(
			'*',
			new HttpResponse( 404, '{"error":"not found"}', [] )
		);

		$response = $this->webhook->get( 'https://api.example.com/missing' );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertEquals( 404, $response->getHttpCode() );
		$this->assertEquals( '{"error":"not found"}', $response->getData() );
	}

	public function testPostWithoutParameters()
	{
		$this->httpClient->addResponse(
			'*',
			new HttpResponse( 201, '{"id":1,"created":true}', [] )
		);

		$response = $this->webhook->post( 'https://api.example.com/submit' );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertEquals( 201, $response->getHttpCode() );
		$this->assertEquals( '{"id":1,"created":true}', $response->getData() );
	}

	public function testPostWithParameters()
	{
		$this->httpClient->addResponse(
			'*',
			new HttpResponse( 200, '{"authenticated":true,"token":"abc123"}', [] )
		);

		$params = [
			'username' => 'testuser',
			'password' => 'testpass',
			'action' => 'login'
		];

		$response = $this->webhook->post( 'https://api.example.com/auth', $params );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertEquals( 200, $response->getHttpCode() );

		// Verify the request was made correctly
		$requests = $this->httpClient->getRequests();
		$this->assertCount( 1, $requests );
		$this->assertEquals( 'POST', $requests[0]['method'] );
		$this->assertEquals( $params, $requests[0]['data'] );
	}

	public function testPostWithEmptyParameters()
	{
		$this->httpClient->addResponse(
			'*',
			new HttpResponse( 200, '{"success":true}', [] )
		);

		$response = $this->webhook->post( 'https://api.example.com/submit', [] );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertEquals( 200, $response->getHttpCode() );
	}

	public function testPostJson()
	{
		$this->httpClient->addResponse(
			'*',
			new HttpResponse( 201, '{"id":42,"created":true}', [] )
		);

		$json = json_encode([
			'name' => 'Test User',
			'email' => 'test@example.com',
			'active' => true
		]);

		$response = $this->webhook->postJson( 'https://api.example.com/api/users', $json );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertEquals( 201, $response->getHttpCode() );

		// Verify the request was made correctly
		$requests = $this->httpClient->getRequests();
		$this->assertCount( 1, $requests );
		$this->assertEquals( 'POST', $requests[0]['method'] );
		$this->assertEquals( ['json' => $json], $requests[0]['data'] );
	}

	public function testPostJsonWithEmptyJson()
	{
		$this->httpClient->addResponse(
			'*',
			new HttpResponse( 200, '{"received":true}', [] )
		);

		$json = '{}';
		$response = $this->webhook->postJson( 'https://api.example.com/api/data', $json );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertEquals( 200, $response->getHttpCode() );
	}

	public function testPostJsonWithComplexJson()
	{
		$this->httpClient->addResponse(
			'*',
			new HttpResponse( 200, '{"processed":true}', [] )
		);

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
				'timestamp' => 1234567890,
				'version' => '1.0'
			]
		]);

		$response = $this->webhook->postJson( 'https://api.example.com/api/complex', $json );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertEquals( 200, $response->getHttpCode() );
	}

	public function testErrorHandling()
	{
		// Use addErrorResponse helper method
		$this->httpClient->addErrorResponse( '*', 'Failed to connect to host', 7 );

		$response = $this->webhook->get( 'https://invalid.example.com/test' );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertEquals( 7, $response->getError() );
		$this->assertEquals( 'Failed to connect to host', $response->getErrorString() );
	}

	public function testMultipleSequentialRequests()
	{
		// Program multiple responses for different URLs
		$this->httpClient->addResponse(
			'*test1*',
			new HttpResponse( 200, '{"result":"test1"}', [] )
		);
		$this->httpClient->addResponse(
			'*test2*',
			new HttpResponse( 200, '{"result":"test2"}', [] )
		);
		$this->httpClient->addResponse(
			'*test3*',
			new HttpResponse( 200, '{"result":"test3"}', [] )
		);

		// Make multiple requests
		$response1 = $this->webhook->get( 'https://api.example.com/test1' );
		$this->assertInstanceOf( WebHookResponse::class, $response1 );
		$this->assertEquals( '{"result":"test1"}', $response1->getData() );

		$response2 = $this->webhook->post( 'https://api.example.com/test2' );
		$this->assertInstanceOf( WebHookResponse::class, $response2 );
		$this->assertEquals( '{"result":"test2"}', $response2->getData() );

		$response3 = $this->webhook->postJson( 'https://api.example.com/test3', '{"test": true}' );
		$this->assertInstanceOf( WebHookResponse::class, $response3 );
		$this->assertEquals( '{"result":"test3"}', $response3->getData() );

		// Verify all three requests were recorded
		$requests = $this->httpClient->getRequests();
		$this->assertCount( 3, $requests );
	}

	public function testGetParameterEncoding()
	{
		$this->httpClient->addResponse(
			'*',
			new HttpResponse( 200, '{"results":[]}', [] )
		);

		$params = [
			'search' => 'test query',
			'page' => '1',
			'limit' => '10'
		];

		// Test that parameters are properly formatted
		$response = $this->webhook->get( 'https://api.example.com/search', $params );

		$this->assertInstanceOf( WebHookResponse::class, $response );
		$this->assertEquals( 200, $response->getHttpCode() );

		// Verify request was recorded with URL containing query string
		$requests = $this->httpClient->getRequests();
		$this->assertStringContainsString( 'search=test+query', $requests[0]['url'] );
	}

	public function testResponseConversion()
	{
		// Test that IHttpResponse is correctly converted to WebHookResponse
		$this->httpClient->addResponse(
			'*',
			new HttpResponse( 201, 'Created', ['Location' => '/resource/123'] )
		);

		$response = $this->webhook->post( 'https://api.example.com/resource', ['name' => 'test'] );

		// Verify all properties are correctly mapped
		$this->assertEquals( 201, $response->getHttpCode() );
		$this->assertEquals( 'Created', $response->getData() );
		$this->assertEquals( 0, $response->getError() );
		$this->assertEquals( '', $response->getErrorString() );
	}

	public function testConvertResponseProtectedMethod()
	{
		// Test the protected convertResponse method using reflection
		$httpResponse = new HttpResponse( 200, 'test body', [] );

		$reflection = new \ReflectionClass( $this->webhook );
		$method = $reflection->getMethod( 'convertResponse' );

		$webHookResponse = $method->invoke( $this->webhook, $httpResponse );

		$this->assertInstanceOf( WebHookResponse::class, $webHookResponse );
		$this->assertEquals( 200, $webHookResponse->getHttpCode() );
		$this->assertEquals( 'test body', $webHookResponse->getData() );
	}

	public function testTimeoutIsSet()
	{
		// Create a new webhook which should set timeout on construction
		$client = new MemoryHttpClient();
		$webhook = new WebHook( $client );

		// The constructor should have called setTimeout(10)
		// We can verify this by checking that MemoryHttpClient received the call
		// MemoryHttpClient stores timeout internally
		$reflection = new \ReflectionClass( $client );
		$property = $reflection->getProperty( 'timeout' );

		$this->assertEquals( 10, $property->getValue( $client ) );
	}
}

