<?php
/**
 * Clear Cache Task
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

App::uses('CommonAppShell', 'Common.Console');
App::uses('CacheManager', 'Webmaster.Cache');

/**
 * Clear Cache Task
 *
 * @package       Webmaster.Console.Command.Task
 */
class ClearCacheTask extends CommonAppShell {

/**
 * Execution method always used for tasks
 *
 * @return void
 */
	public function execute() {
		extract($this->params);

		if (!isset($config) || empty($config)) {
			$configs = Cache::configured();
		} else {
			$configs = array($config);
		}

		foreach ($configs as $config) {
			if (Cache::isInitialized($config)) {
				$cleared = CacheManager::clear(!$expired, $config);
				$this->out(__d('webmaster', "The '%s' cache configuration was successfully cleared.", $config));
			} else {
				$this->out(__d('webmaster', "The '%s' cache configuration has not been initialized.", $config));
			}
		}
		$this->out();
	}

/**
 * {@inheritdoc}
 */
	public function getOptionParser() {
		return ConsoleOptionParser::buildFromArray(array(
			'command' => 'clear_cache',
			'description' => array(
				__d('webmaster', "Clear application's cache"),
			),
			'options'	=> array(
				'expired' => array(
					'help' => __d('webmaster', 'Cache configuration to use in conjunction with `toolkit clear`.'),
					'short' => 'e',
					'boolean' => true,
					'default' => false
				),
				'config' => array(
					'help' => __d('webmaster', "Specify the cache configuration's name."),
					'short' => 'c',
					'choices' => Cache::configured()
				),
			)
		));
	}

}
