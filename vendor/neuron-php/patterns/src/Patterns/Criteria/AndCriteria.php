<?php
/**
 * Created by PhpStorm.
 * User: lee
 * Date: 8/4/16
 * Time: 11:41 AM
 */

namespace Neuron\Patterns\Criteria;

class AndCriteria extends LogicBase implements ICriteria
{
	/**
	 * @param array $entities
	 * @return array
	 */

	public function meetCriteria( array $entities )
	{
		$result = $this->_Criteria->meetCriteria( $entities );

		if( count( $result ) == 0 )
		{
			return $result;
		}

		return $this->_OtherCriteria->meetCriteria( $result );
	}
}
