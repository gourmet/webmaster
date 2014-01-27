<?php
/**
 * WebmasterController
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

App::uses('WebmasterAppController', 'Webmaster.Controller');
App::uses('CacheManager', 'Webmaster.Cache');

/**
 * Webmaster controller
 *
 * @package       Webmaster.Controller
 */
class WebmasterController extends WebmasterAppController {

/**
 * {@inheritdoc}
 */
	public $uses = false;

/**
 * {@inheritdoc}
 */
	public function beforeFilter() {
		if ($this->Components->loaded('Auth')) {
			$this->Auth->allow(array('robots', 'sitemap'));
		}
	}

/**
 * Display robots.txt.
 *
 * @return void
 */
	public function robots() {
		$this->autoLayout = false;

		$File = $this->_getRobotsFile();
		if (!$File->exists()) {
			throw new NotFoundException(__d('webmaster', "Missing the `robots.txt` file."));
		}

		$this->set('contents', $File->read());
	}

/**
 * Build sitemap.xml.
 *
 * @return void
 */
	public function sitemap() {
		Configure::write('debug', 0);

		// Skip the view file by defining some XmlView magic
		$this->set('_rootNode', 'urlset');
		$this->set('_serialize', 'url');

		$cacheKey = Common::read('Webmaster.cache.keys.sitemap', 'sitemap');
		$cacheConfig = Common::read('Webmaster.cache.config', 'default');
		$cacheDisabled = Common::read('Webmaster.cache.forceDisable', false);

		// Load from cache when possible.
		if (!$cacheDisabled) {
			$url = Cache::read($cacheKey, $cacheConfig);

			if (!empty($url)) {
				$this->set(compact('url'));
				return;
			}
		}

		$url = array();

		foreach ((array) $this->triggerEvent('Webmaster.sitemap', $this) as $link) {
			extract($link);

			if (!isset($loc)) {
				continue;
			}

			foreach (array('lastmod', 'changefreq', 'priority') as $key) {
				if (!isset($$key)) {
					$$key = Configure::read('Webmaster.sitemap.' . $key);
				}
			}

			$lastmod = date('Y-m-d\Th:mP', strtotime($lastmod));

			$url[] = compact('loc', 'lastmod', 'changefreq', 'priority');
		}

		if (!$cacheDisabled) {
			Cache::write($cacheKey, $url, $cacheConfig);
		}

		$this->set(compact('url'));
	}

/**
 * Webmaster dashboard.
 *
 * @return void
 */
	public function admin_index() {
		if (!$this->_robotsExists() && !$this->_getRobotsFile()->exists()) {
			$this->flash('robots.invalid');
		}

		$this->set('usage', CacheManager::usage());
		$this->set('memory', Server::memory(false));
		$this->set('server', Server::info(false));
		$this->set('system', Server::system(false));
	}

/**
 * Display `phpinfo()`.
 *
 * @return void
 */
	public function admin_phpinfo() {

	}

/**
 * Manage `robots.txt`.
 *
 * @return void
 */
	public function admin_robots() {
		if ($this->_robotsExists()) {
			$this->flash('robots.exists');
		}

		$File = $this->_getRobotsFile();

		$this->request->data = array_merge(
			array('robots' => $File->exists() ? $File->read() : ''),
			$this->request->data
		);

		// @see http://www.webmasterworld.com/robots_txt/3310622.htm
		$sitemap = sprintf('Sitemap: %ssitemap.xml', Router::url('/', true));
		$sitemapIncluded = false !== strpos($this->request->data['robots'], $sitemap);
		$this->set('sitemap', $sitemapIncluded);

		if (!$this->request->is('post')) {
			return;
		}

		if (!$sitemapIncluded && !empty($this->request->data['sitemap'])) {
			$this->request->data['robots'] = $sitemap . "\n" . $this->request->data['robots'];
		}

		if (($File->exists() || $File->create()) && $File->write($this->request->data['robots'])) {
			$this->flash('robots.success');
		}

		if (Configure::read('debug')) {
			throw new InternalErrorException(__d('webmaster', "Could not write `robots.txt` to %s", TMP));
		}

		$this->flash('robots.fail');
	}

/**
 * Get instance of `File('TMP/robots.txt')`.
 *
 * @return File Instance of robots.txt.
 */
	protected function _getRobotsFile() {
		App::uses('File', 'Utility');
		return new File(TMP . 'robots.txt');
	}

/**
 * Check if the robots.txt file already exists in `APP/webroot`.
 *
 * @return boolean True if it exists, false if not.
 */
	protected function _robotsExists() {
		return file_exists(APP . 'webroot' . DS . 'robots.txt');
	}

}
