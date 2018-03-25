<?php

namespace Reich\PHP;

set_time_limit(25);

use Closure;

class LongPolling
{
	/**
	 * Stores the current timestamp.
	 *
	 * @var int
	 */
	private static $start = null;

	/**
	 * Timeout in seconds
	 *
	 * @return int
	 */
	private static $timeout = 20;

	/**
	 * Stores the md5 of the content.
	 *
	 * @var string
	 */
	private static $eTag = '';

	/**
	 * Creates a listener for changes.
	 *
	 * @param int | $milliseconds
	 * @param Closure | $callback
	 * @return void 
	 */
	public static function check($milliseconds, Closure $callback)
	{
		clearstatcache();

		self::$start = isset($_GET['timestamp']) ? (int)$_GET['timestamp'] : null;
		self::$timeout = self::$start + 20;
		self::$eTag = md5($callback());
		$microseconds = ($milliseconds < 300) ? 300 * 1000 : $milliseconds * 1000;

		do {
			$results = $callback();

			if (is_null(self::$start)) {
				self::sendData($results);
				break;
			}

			if (is_null($results)) {
				echo 'Callback must return something...';
				break;
			}

			$changed = md5($results) != self::$eTag;
			$timedOut = self::$timeout <= time();

			if ($changed || $timedOut) {
				self::sendData($results, $changed);
				break;
			}

			usleep($microseconds);
		} while(true);
	}

	/**
	 * Sends the data back to the client.
	 * Because the ajax request timed out or 
	 * because the data has been changed.
	 *
	 * @param mixed | $results
	 * @param bool | $changed
	 * @return void
	 */
	protected static function sendData($results, $changed = false)
	{
		$results = [
			'content' => $results,
			'timestamp' => time(),
			'changed' => $changed
		];
		
		header('ETag: ' . self::$eTag, true);
		header('Content-Type: application/json', true);
		echo json_encode($results);
	}
}