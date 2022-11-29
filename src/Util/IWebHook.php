<?php

namespace Neuron\Util;

/**
 * webhook interface
 */
interface IWebHook
{
	public function get(  string $Url, array $Params );
	public function post( string $Url, array $Params );
}
