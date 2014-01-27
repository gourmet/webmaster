<?php

// Define default sitemap settings.
if (!Configure::check('Webmaster.sitemap')) {
	Configure::write('Webmaster.sitemap', array(
		'lastmod' => '-2 weeks',
		'changefreq' => 'monthly', // always, hourly, daily, weekly, monthly, yearly, never
		'priority' => '0.5' // from 0 to 1
	));
}
