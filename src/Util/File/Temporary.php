<?php

namespace Neuron\Util\File;

/**
 * Temporary file management.
 */

class Temporary
{
	/**
	 * @param string $Directory
	 * @return string
	 */

	static public function getFile( string $Directory = '' ) : string
	{
		if( !$Directory )
		{
			$Directory = sys_get_temp_dir();
		}

		$Filename = '';

		$bFound = false;

		while( !$bFound )
		{
			$Filename = $Directory.'/'.uniqid( 'Temp', true );

			if( !file_exists( $Filename ) )
			{
				$bFound = true;
			}
		}

		return $Filename;
	}
}
