<?php
namespace Neuron\Patterns\Observer;

/**
 * Class Observable
 * @package Neuron\Patterns\Observer
 */

class Observable
{
	private $_aObservers = [];

	/**
	 * Add an observer to the notification list.
	 * @param IObserver $Observer
	 */

	public function register( IObserver $Observer )
	{
		$this->_aObservers[] = $Observer;
	}

	/**
	 * @param mixed $params, ...
	 */

	public function notifyObservers( ...$params )
	{
		foreach( $this->_aObservers as $Observer )
		{
			$Observer->notify( $this, ...$params );
		}
	}
}
