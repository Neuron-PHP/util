<?php

namespace Neuron\Util;

class WebHookResponse
{
	private $_HttpCode;
	private $_Data;
	private $_Error;
	private $_ErrorString;

	/**
	 * @return mixed
	 */
	public function getHttpCode()
	{
		return $this->_HttpCode;
	}

	/**
	 * @param mixed $HttpCode
	 * @return WebHookResponse
	 */
	public function setHttpCode( $HttpCode ) : WebHookResponse
	{
		$this->_HttpCode = $HttpCode;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getData()
	{
		return $this->_Data;
	}

	/**
	 * @param mixed $Data
	 * @return WebHookResponse
	 */
	public function setData( $Data ) : WebHookResponse
	{
		$this->_Data = $Data;
		return $this;
	}

	/**
	 * @return mixed
	 */
	public function getError()
	{
		return $this->_Error;
	}

	/**
	 * @param mixed $Error
	 * @return WebHookResponse
	 */
	public function setError( $Error )
	{
		$this->_Error = $Error;
		return $this;
	}

	/**
	 * @return string
	 */
	public function getErrorString() : string
	{
		return $this->_ErrorString;
	}

	/**
	 * @param string $ErrorString
	 * @return WebHookResponse
	 */
	public function setErrorString( string $ErrorString )
	{
		$this->_ErrorString = $ErrorString;
		return $this;
	}
}
