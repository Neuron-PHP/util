<?php

namespace Neuron\Util;

interface IWebHook
{
	public function get( $Url, array $Params );
	public function post( $Url, array $Params );
}
