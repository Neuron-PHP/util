<?php

namespace Neuron\Patterns\Observer;

/**
 * Interface IObserver
 * @package Neuron\Patterns\Observer
 */

interface IObserver
{
	/**
	 * @param Observable $Observable
	 * @param $param
	 * @return mixed
	 */

	function notify( Observable $Observable, ...$param );
}
