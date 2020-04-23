<?php

namespace Neuron\Patterns\Singleton;

class Session extends Base
{
	public function serialize()
	{
		$_SESSION[ get_called_class() ] = $this;
	}

	public static function invalidate()
	{
		unset( $_SESSION[ get_called_class() ] );
	}

	public static function instance()
	{
		return isset( $_SESSION[ get_called_class() ] ) ? $_SESSION[ get_called_class() ] : false;
	}
}
