<?php

namespace Neuron\Util;

class WebHookResponse
{
	private $_httpCode;
	private $_data;
	private $_error;
	private $_errorString;

	/**
	 * @return mixed
	 */
	public function getHttpCode()
	{
		return $this->_httpCode;
	}

	/**
	 * @param mixed $httpCode
	 * @return WebHookResponse
	 */
	public function setHttpCode( $httpCode ) : WebHookResponse
	{
		$this->_httpCode = $httpCode;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getData()
	{
		return $this->_data;
	}

	/**
	 * @param mixed $data
	 * @return WebHookResponse
	 */
	public function setData( $data ) : WebHookResponse
	{
		$this->_data = $data;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getError()
	{
		return $this->_error;
	}

	/**
	 * @param mixed $error
	 * @return WebHookResponse
	 */
	public function setError( $error )
	{
		$this->_error = $error;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getErrorString() : string
	{
		return $this->_errorString;
	}

	/**
	 * @param string $errorString
	 * @return WebHookResponse
	 */
	public function setErrorString( string $errorString )
	{
		$this->_errorString = $errorString;
		return $this;
	}
}
