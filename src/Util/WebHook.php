<?php

namespace Neuron\Util;

class WebHook implements IWebHook
{
	private $_Handle;

	public function __construct()
	{
		$this->_Handle = curl_init();
		curl_setopt( $this->_Handle, CURLOPT_RETURNTRANSFER, true );
	}

	/**
	 * @return WebHookResponse
	 */
	protected function getResponse() : WebHookResponse
	{
		$Response = new WebHookResponse();

		$Response->setData( curl_exec( $this->_Handle ) );

		$Response->setError( curl_errno( $this->_Handle ) );
		$Response->setErrorString( curl_error( $this->_Handle ) );
		$Response->setHttpCode( curl_getinfo( $this->_Handle, CURLINFO_HTTP_CODE ) );

		return $Response;
	}

	/**
	 * @param $Url
	 * @param array $Params
	 * @return mixed
	 */
	public function get( $Url, array $Params = [] ) : WebHookResponse
	{
		$ParamString = '';

		foreach( $Params as $Name => $Value )
		{
			if( $ParamString )
			{
				$ParamString .= '&';
			}

			$ParamString .= "$Name=$Value";
		}

		if( $ParamString )
		{
			$Url .= "?$ParamString";
		}

		curl_setopt( $this->_Handle, CURLOPT_URL, $Url );

		$Response = $this->getResponse();

		curl_close( $this->_Handle );

		return $Response;
	}

	/**
	 * @param $Url
	 * @param array $Params
	 * @return mixed
	 */
	public function post( $Url, array $Params = [] ) : WebHookResponse
	{
		curl_setopt_array(
			$this->_Handle,
			[
				CURLOPT_URL            => $Url,
				CURLOPT_POST           => true,
				CURLOPT_POSTFIELDS     => $Params
			]
		);

		$Response = $this->getResponse();

		curl_close( $this->_Handle );

		return $Response;
	}

	/**
	 * @param $Url
	 * @param string $Json
	 * @return WebHookResponse
	 */
	public function postJson( $Url, string $Json ) : WebHookResponse
	{
		curl_setopt_array(
			$this->_Handle,
			[
				CURLOPT_URL           => $Url,
				CURLOPT_CUSTOMREQUEST => 'POST',
				CURLOPT_POSTFIELDS    => $Json,
				CURLOPT_HTTPHEADER    => [
					'Content-Type: application/json',
					'Content-Length: ' . strlen( $Json )
				]
			]
		);

		$Response = $this->getResponse();

		curl_close( $this->_Handle );

		return $Response;
	}
}
