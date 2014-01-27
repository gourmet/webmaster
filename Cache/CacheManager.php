<?php
/**
 * Cache Manager
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

App::uses('Cache', 'Cache');
App::uses('Server', 'Webmaster.Core');

/**
 * CacheManager
 *
 * @package       Webmaster.Cache
 */
class CacheManager {
/**
 * Clear cache.
 *
 * @param boolean $check If true will check expiration, otherwise delete all
 * @param string $config Name of the configuration to use. Defaults to 'default'
 * @return boolean True if the cache was successfully cleared, false otherwise
 */
	public static function clear($check = true, $config = 'default') {
		$EventManager = new CommonEventManager();
		$EventManager->loadListeners($EventManager, 'Cache');
		$Event = new CakeEvent('Cache.clear', null, compact('check', 'config'));
		list($Event->break, $Event->breakOn) = array(true, false);
		$EventManager->trigger($Event);
		if (false === $Event->result) {
			return false;
		}

		return Cache::clear($check, $config);
	}

/**
 * Get cache usage.
 *
 * @return array Usage results.
 */
	public static function usage() {
		$total = disk_total_space('/');
		$avail = disk_free_space('/');

		return array(
			'files' => array(
				'used' => Server::convert(Server::fileSize(glob(CACHE . '{.,*}' . DS . '*', GLOB_BRACE))),
				'total' => Server::convert($total),
				'available' => Server::convert($avail)
			),
			'assets' => array(
				'used' => Server::convert(Server::fileSize(glob(APP . 'webroot' . DS . '*' . DS . '*'))),
				'total' => Server::convert($total),
				'available' => Server::convert($avail)
			)
		);
	}

}
