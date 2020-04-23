<?php

namespace Neuron\Patterns\Criteria;

class KeyValue extends Base implements ICriteria
{
	private $_Key;
	private $_Value;

	public function __construct( $Key, $Value )
	{
		$this->_Key   = $Key;
		$this->_Value = $Value;
	}

	public function meetCriteria( array $entities )
	{
		$aResults = [];

		foreach( $entities as $item )
		{
			if( $item[ $this->_Key ] == $this->_Value )
			{
				$aResults[] = $item;
			}
		}

		return $aResults;
	}
}
