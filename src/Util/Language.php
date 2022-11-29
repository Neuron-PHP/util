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

	private $_aText;

	/**
	 * Loads csv data from a string. Format: language, id, text
	 * @param $sText
	 * @return bool
	 */
	public function load( $sText )
	{
		if( !$sText )
		{
			return false;
		}

		$Csv = new CSV();

		$aLines = explode( "\n", $sText );

		if( !$aLines )
		{
			return false;
		}

		foreach( $aLines as $Text )
		{
			$Line = $Csv->parse( $Text, [ self::LANGUAGE, self::ID, self::TEXT ] );

			$lang  = trim( $Line[ self::LANGUAGE ] );
			$ident = trim( $Line[ self::ID ] );

			$this->_aText[ $lang ][ $ident ] = trim( $Line[ self::TEXT ] );
		}

		return true;
	}

	/**
	 * Sets the current language in a session variable.
	 * @param $sLanguage
	 */

	public function setLanguage( $sLanguage )
	{
		$_SESSION[ 'language' ] = $sLanguage;
	}

	/**
	 * Gets the current language text for an id.
	 * @param $sId
	 * @param string $sLanguage
	 * @return string
	 */
	public function getText( $sId, $sLanguage = '' )
	{
		if( !$sId )
		{
			return null;
		}

		if( !$sLanguage )
		{
			$sLanguage = $_SESSION[ 'language' ];
		}

		if( array_key_exists( $sLanguage, $this->_aText ) )
		{
			$Language = $this->_aText[ $sLanguage ];

			if( array_key_exists( $sId, $Language ) )
			{
				return $Language[ $sId ];
			}
		}

		return null;
	}
}
