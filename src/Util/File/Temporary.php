<?php

namespace Neuron\Util\File;

/**
 * Temporary file management.
 */

class Temporary
{
	/**
	 * @param string $directory
	 * @return string
	 */

	static public function getFile( string $directory = '' ) : string
	{
		if( !$directory )
		{
			$directory = sys_get_temp_dir();
		}

		$filename = '';

		$found = false;

		while( !$found )
		{
			$filename = $directory.'/'.uniqid( 'Temp', true );

			if( !file_exists( $filename ) )
			{
				$found = true;
			}
		}

		return $filename;
	}
}
