<?php
namespace Neuron\Patterns;

use Neuron\Patterns\Singleton;

class Registry extends Singleton\Memory
{
	private $_Objects = [];

	public function __construct()
	{}

	/**
	 * @param $name
	 * @param $object
	 */

	public function set( $name, $object )
	{
		$this->_Objects[ $name ] = $object;
	}

	/**
	 * @param $name
	 * @return value
	 */

	public function get( $name )
	{
		if( !array_key_exists( $name, $this->_Objects ) )
		{
			return null;
		}

		return $this->_Objects[ $name ];
	}
}
