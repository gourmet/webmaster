<?php
/**
 * WebmasterEventListener
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

App::uses('CakeEventListener', 'Event');

/**
 * Webmaster event listener
 *
 * Collection of webmaster events to load.
 *
 * @package       Common.Event
 */
class WebmasterEventListener implements CakeEventListener {

	public function implementedEvents() {
		return array(
			'Controller.constructClasses' => array('callable' => 'controllerConstructClasses'),
			'Routes.connect' => array('callable' => 'routesConnect'),
			'Webmaster.sitemap' => array('callable' => 'webmasterSitemap'),
		);
	}

	public function controllerConstructClasses(CakeEvent $Event) {
		$Event->result['components'][] = 'RequestHandler';

		$default = array('plugin' => 'webmaster', 'controller' => 'webmaster', 'action' => 'robots');
		$link = sprintf('<a href="%s">%s</a>', Router::url($default), __d('webmaster', "Create it now"));
		$filename = '`robots.txt`';

		$Event->result = Hash::merge((array) $Event->result, array('alertMessages' => array(
			'robots.success' => array(
				'message' => __d('webmaster', "Your %s file was successfully updated.", $filename),
				'redirect' => Router::url(array_merge($default, array('action' => 'dashboard')))
			),
			'robots.fail' => array(
				'message' => __d('webmaster', "There was a problem updating your %s file", $filename),
				'level' => 'error',
			),
			'robots.exists' => array(
				'message' => __d(
					'webmaster',
					"A %s was already manually created in your application's webroot. " .
					"To dynamically manage it, you will need to delete it first.",
					$filename
				),
				'level' => 'error',
				'redirect' => true,
				'dismiss' => true
			),
			'robots.invalid' => array(
				'message' => __d('webmaster', "Your %s file does not exist. %s.", $filename, $link),
				'level' => 'warning',
				'redirect' => null,
				'dismiss' => true
			)
		)));

		Navigation::add('Admin.webmaster', array(
			'access' => 'User.admin',
			'title' => __d('webmaster', "Webmaster"),
			'url' => array('plugin' => 'webmaster', 'controller' => 'webmaster', 'action' => 'index', 'prefix' => 'admin', 'admin' => true),
			'weight' => 9090
		));

		Navigation::add('Admin.webmaster.children.manage_robots', array(
			'access' => 'User.admin',
			'title' => __d('webmaster', "Manage Robots"),
			'url' => array('plugin' => 'webmaster', 'controller' => 'webmaster', 'action' => 'robots', 'prefix' => 'admin', 'admin' => true),
			'weight' => 1000,
		));

		Navigation::add('Admin.webmaster.children.robots', array(
			'access' => 'User.admin',
			'title' => __d('webmaster', "Preview Robots"),
			'url' => Router::url('/robots.txt', true),
			'weight' => 2000,
			'htmlAttributes' => array('a' => array('target' => '_blank'))
		));

		Navigation::add('Admin.webmaster.children.sitemap', array(
			'access' => 'User.admin',
			'title' => __d('webmaster', "Preview Sitemap"),
			'url' => Router::url('/sitemap.xml', true),
			'weight' => 3000,
			'htmlAttributes' => array('a' => array('target' => '_blank'))
		));
	}

	public function webmasterSitemap(CakeEvent $Event) {
		$Event->result[] = array(
			'loc' => Router::url('/', true)
		);
	}

}
