<?php

namespace Neuron\Util;

use Neuron\Core\System\IFileSystem;
use Neuron\Core\System\RealFileSystem;

/**
 * Basic templating engine. Replaces %item% with $fields[ 'item' ].
 */
class Template
{
	/**
	 * @param string $file
	 * @param array $fields
	 * @param IFileSystem|null $fs File system implementation (null = use real file system)
	 * @return mixed
	 */
	static function fromFile( string $file, array $fields, ?IFileSystem $fs = null )
	{
		$fs = $fs ?? new RealFileSystem();
		$file = "templates/$file";

		if( !$fs->fileExists( $file ) )
		{
			return null;
		}

		$text = $fs->readFile( $file );

		if( $text === false )
		{
			return null;
		}

		return self::fromText( $text, $fields );
	}

	/**
	 * @param string $text
	 * @param array $fields
	 * @return string
	 */
	static function fromText( string $text, array $fields ): string
	{
		foreach( $fields as $field => $data )
		{
			$find = '%'.$field.'%';

			$text = str_replace( $find, $data, $text );
		}

		return $text;
	}
}
