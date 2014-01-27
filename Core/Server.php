<?php
/**
 * Server
 *
 * PHP 5
 *
 * Copyright 2013, Jad Bitar (http://jadb.io)
 *
 * Licensed under The MIT License
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright 2013, Jad Bitar (http://jadb.io)
 * @link          http://github.com/gourmet/webmaster
 * @since         0.1.0
 * @license       MIT License (http://www.opensource.org/licenses/mit-license.php)
 */

/**
 * Server
 *
 * @package       Webmaster.Core
 */
class Server {

/**
 * Size converter.
 *
 * @param integer $size Size to convert.
 * @return string Converted size.
 */
	public static function convert($size) {
		if (!$size) {
			return '0 b';
		}
		$unit = array('b', 'kb', 'mb', 'gb', 'tb', 'pb');
		return @round($size / pow(1024, ($i = floor(log($size, 1024)))), 2) . ' ' . $unit[$i];
	}

/**
 * Get the size of a list of files.
 *
 * @param array $files Files to measure.
 * @return integer Total files size.
 */
	public static function fileSize(array $files) {
		$size = 0;
		array_walk($files, function($file) use(&$size) {
			$size += filesize($file);
		});

		return $size;
	}

/**
 * Get system memory information.
 *
 * @param boolean $convert Whether or not to convert sizes.
 * @return array Resulset.
 */
	public static function memory($convert = true) {
		$memory = array(
			'current' => memory_get_usage(),
			'current_t' => memory_get_usage(true),
			'max' => memory_get_peak_usage(),
			'max_' => memory_get_peak_usage(true),
			'limit' => ini_get('memory_limit')
		);

		if ($convert) {
			$memory['current']   = self::convert($memory['current']);
			$memory['current_t'] = self::convert($memory['current_t']);
			$memory['max']       = self::convert($memory['max']);
			$memory['max_']      = self::convert($memory['max_']);
		}

		return $memory;
	}

/**
 * Get system information.
 *
 * @return mixed
 */
	public static function info() {
		// try file method
		$load = @file_get_contents('/proc/loadavg');
		if ($load) {
			$load = explode(' ', $load, 4);
			unset($load[3]);
		}

		// try exec
		if (!$load) {
			$load = @exec('uptime');

			// try shell_exec
			if (!$load) {
				$load = @shell_exec('uptime');
			}

			if ($load) {
				$load = explode(' ', $load);
				$load[2] = trim(array_pop($load));
				$load[1] = str_replace(',', '', array_pop($load));
				$load[0] = str_replace(',', '', array_pop($load));
			} else {
				$load[0] = $load[1] = $load[2] = -1;
			}
		}

		return $load;
	}

/**
 * Get system information.
 *
 * @param boolean $extensions Whether or not to get loaded PHP extensions.
 * @return array Resultset.
 */
	public static function system($extensions = false) {
		$return = array();
		$return['Server'] = array(
			'name' => php_uname('n'),
			'os' => php_uname('s'),
			'type' => php_uname('s'),
			'version' => php_uname('v'),
			'release' => php_uname('r'),
		);
		$return['Php'] = array(
			'version' => phpversion(),
			'memory_limit' => ini_get('memory_limit'),
			'sapi' => php_sapi_name()
		);

		if (!$extensions) {
			return $return;
		}

		$extensions = get_loaded_extensions();
		foreach ($extensions as $extension) {
			$return['Php']['extensions'][] = array(
				'name' => $extension,
				'version' => phpversion($extension)
			);
		}

		return $return;
	}
}
