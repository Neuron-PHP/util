<?php

namespace Neuron\Util;

use Neuron\Core\System\IHttpClient;
use Neuron\Core\System\IHttpResponse;
use Neuron\Core\System\RealHttpClient;

class WebHook implements IWebHook
{
	private IHttpClient $httpClient;

	public function __construct( ?IHttpClient $httpClient = null )
	{
		$this->httpClient = $httpClient ?? new RealHttpClient();
		$this->httpClient->setTimeout( 10 );
	}

	/**
	 * Convert IHttpResponse to WebHookResponse for backward compatibility
	 *
	 * @param IHttpResponse $httpResponse
	 * @return WebHookResponse
	 */
	protected function convertResponse( IHttpResponse $httpResponse ): WebHookResponse
	{
		$response = new WebHookResponse();

		$response->setData( $httpResponse->getBody() );
		$response->setHttpCode( $httpResponse->getStatusCode() );
		$response->setError( $httpResponse->getErrorCode() );
		$response->setErrorString( $httpResponse->getError() );

		return $response;
	}

	/**
	 * @param $url
	 * @param array $params
	 * @return WebHookResponse
	 */
	public function get( string $url, array $params = [] ) : WebHookResponse
	{
		$httpResponse = $this->httpClient->get( $url, $params );
		return $this->convertResponse( $httpResponse );
	}

	/**
	 * @param $url
	 * @param array $params
	 * @return WebHookResponse
	 */
	public function post( string $url, array $params = [] ) : WebHookResponse
	{
		$httpResponse = $this->httpClient->post( $url, $params );
		return $this->convertResponse( $httpResponse );
	}

	/**
	 * @param string $url
	 * @param string $json
	 * @return WebHookResponse
	 */
	public function postJson( string $url, string $json ) : WebHookResponse
	{
		$httpResponse = $this->httpClient->postJson( $url, $json );
		return $this->convertResponse( $httpResponse );
	}
}
