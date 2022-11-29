<?php

namespace Neuron\Util;

/**
 * System level methods.
 */

class System
{
	/**
	 * @return bool
	 * Returns true if application was run from the command line.
	 */

	public static function isCommandLine()
	{
		return ( php_sapi_name() == 'cli' );
	}
}
