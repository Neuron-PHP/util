<?php

namespace Neuron\Util;

/**
 * webhook interface
 */
interface IWebHook
{
	public function get(  string $url, array $params );
	public function post( string $url, array $params );
}
