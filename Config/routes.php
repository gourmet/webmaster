<?php
/**
 * Route's defaults.
 *
 * @var array
 */
	$defaults = array('plugin' => 'webmaster', 'controller' => 'webmaster');

/**
 * Force usage of the CakeRoute class to avoid duplicates that are useless anyways
 * (i.e. /fr/sitemap.xml, /sp/robots.txt).
 *
 * @var array
 */
	$options = array('routeClass' => 'CakeRoute');

/**
 * Add the `txt` and `xml` extensions.
 */
	Router::setExtensions(array('txt', 'xml'));

/**
 * Route `/robots.txt`.
 */
	Router::connect('/robots', array_merge($defaults, array('action' => 'robots', 'ext' => 'txt')), $options);
	Router::promote();

/**
 * Route `/sitemap.xml`.
 */
	Router::connect('/sitemap', array_merge($defaults, array('action' => 'sitemap', 'ext' => 'xml')), $options);
	Router::promote();

	unset($options, $defaults);
