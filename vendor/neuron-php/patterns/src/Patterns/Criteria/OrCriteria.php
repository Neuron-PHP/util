<?php
/**
 * Created by PhpStorm.
 * User: lee
 * Date: 8/4/16
 * Time: 11:53 AM
 */

namespace Neuron\Patterns\Criteria;

use Neuron\Data\ArrayHelper;

class OrCriteria extends LogicBase implements ICriteria
{
	/**
	 * @param array $entities
	 * @return array
	 */

	public function meetCriteria( array $entities )
	{
		$aFirstCriteriaItems = $this->_Criteria->meetCriteria( $entities );
		$aOtherCriteriaItems = $this->_OtherCriteria->meetCriteria( $entities );

		foreach( $aOtherCriteriaItems as $Item )
		{
			if( !ArrayHelper::contains( $aFirstCriteriaItems, $Item ) )
			{
				$aFirstCriteriaItems[] = $Item;
			}
		}

		return $aFirstCriteriaItems;
	}
}
