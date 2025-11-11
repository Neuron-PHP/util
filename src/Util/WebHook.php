<?php

namespace Neuron\Util;

class WebHook implements IWebHook
{
	private $_handle;

	public function __construct()
	{
		$this->_handle = curl_init();
		curl_setopt( $this->_handle, CURLOPT_RETURNTRANSFER, true );
	}

	/**
	 * @return WebHookResponse
	 */
	protected function getResponse() : WebHookResponse
	{
		$response = new WebHookResponse();

		$response->setData( curl_exec( $this->_handle ) );

		$response->setError( curl_errno( $this->_handle ) );
		$response->setErrorString( curl_error( $this->_handle ) );
		$response->setHttpCode( curl_getinfo( $this->_handle, CURLINFO_HTTP_CODE ) );

		return $response;
	}

	/**
	 * @param $url
	 * @param array $params
	 * @return mixed
	 */
	public function get( string $url, array $params = [] ) : WebHookResponse
	{
		$paramString = '';

		foreach( $params as $name => $value )
		{
			if( $paramString )
			{
				$paramString .= '&';
			}

			$paramString .= "$name=$value";
		}

		if( $paramString )
		{
			$url .= "?$paramString";
		}

		curl_setopt( $this->_handle, CURLOPT_URL, $url );

		$response = $this->getResponse();

		curl_close( $this->_handle );

		return $response;
	}

	/**
	 * @param $url
	 * @param array $params
	 * @return mixed
	 */
	public function post( string $url, array $params = [] ) : WebHookResponse
	{
		curl_setopt_array(
			$this->_handle,
			[
				CURLOPT_URL            => $url,
				CURLOPT_POST           => true,
				CURLOPT_POSTFIELDS     => $params
			]
		);

		$response = $this->getResponse();

		curl_close( $this->_handle );

		return $response;
	}

	/**
	 * @param string $url
	 * @param string $json
	 * @return WebHookResponse
	 */
	public function postJson( string $url, string $json ) : WebHookResponse
	{
		curl_setopt_array(
			$this->_handle,
			[
				CURLOPT_URL           => $url,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_TIMEOUT		 => 10,
				CURLOPT_POSTFIELDS    => $json,
				CURLOPT_HTTPHEADER    => [
					'Content-Type: application/json',
					'Content-Length: ' . strlen( $json )
				]
			]
		);

		$response = $this->getResponse();

		curl_close( $this->_handle );

		return $response;
	}
}
