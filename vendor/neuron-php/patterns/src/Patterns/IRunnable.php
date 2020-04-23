<?php
namespace Neuron\Patterns;

/**
 * Interface IRunnable
 * @package Neuron\Patterns
 */

interface IRunnable
{
	/**
	 * Generic run method.
	 * @param array|null $aArgv
	 * @return mixed
	 */
	public function run( array $aArgv = null );
}
