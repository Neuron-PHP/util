<?php

namespace Neuron\Util\File;

/**
 * Class Temporary
 * @package Neuron\File
 */

class Temporary
{
	/**
	 * @param string $sDirectory
	 * @return string
	 */

	static public function getFile( $sDirectory = '' )
	{
		if( !$sDirectory )
		{
			$sDirectory = sys_get_temp_dir();
		}

		$sFilename = '';

		$bFound = false;

		while( !$bFound )
		{
			$sFilename = $sDirectory.'/'.uniqid( 'Temp', true );

			if( !file_exists( $sFilename ) )
			{
				$bFound = true;
			}
		}

		return $sFilename;
	}
}
