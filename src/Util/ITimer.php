<?php

namespace Neuron\Util;

/**
 * Class for performing stopwatch functions.
 * @package Neuron\Util
 */
interface ITimer
{
	/**
	 * Start the timer.
	 */
	public function start();

	/**
	 * Stop the timer.
	 */
	public function stop();

	/**
	 * Reset all timer values.
	 */
	public function reset();

	public function lap( string $Name ): int;

	public function getLaps(): array;

	/**
	 * @return int Number of seconds elapsed .
	 */
	public function getElapsed();
}
