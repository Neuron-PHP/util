<?php

namespace Neuron\Util;

use \Neuron\Patterns\Singleton;
use \Neuron\Data\Parser\CSV;

/**
 * Singleton based language dictionary.
 */
class Language extends Singleton\Memcache
{
	const LANGUAGE = 'language';
	const ID       = 'id';
	const TEXT     = 'text';

	private $_text;

	/**
	 * Loads csv data from a string. Format: language, id, text
	 * @param $text
	 * @return bool
	 */
	public function load( $text )
	{
		if( !$text )
		{
			return false;
		}

		$csv = new CSV();

		$lines = explode( "\n", $text );

		if( !$lines )
		{
			return false;
		}

		foreach( $lines as $textItem )
		{
			$line = $csv->parse( $textItem, [ self::LANGUAGE, self::ID, self::TEXT ] );

			$lang  = trim( $line[ self::LANGUAGE ] );
			$ident = trim( $line[ self::ID ] );

			$this->_text[ $lang ][ $ident ] = trim( $line[ self::TEXT ] );
		}

		return true;
	}

	/**
	 * Sets the current language in a session variable.
	 * @param $language
	 */

	public function setLanguage( $language )
	{
		$_SESSION[ 'language' ] = $language;
	}

	/**
	 * Gets the current language text for an id.
	 * @param $id
	 * @param string $language
	 * @return string
	 */
	public function getText( $id, $language = '' )
	{
		if( !$id )
		{
			return null;
		}

		if( !$language )
		{
			$language = $_SESSION[ 'language' ];
		}

		if( array_key_exists( $language, $this->_text ) )
		{
			$languageText = $this->_text[ $language ];

			if( array_key_exists( $id, $languageText ) )
			{
				return $languageText[ $id ];
			}
		}

		return null;
	}
}
