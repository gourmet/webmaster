# CakePHP Webmaster Plugin

@todo write description

## Install

### Composer package

First, add this plugin as a requirement to your `composer.json`:

	{
		"require": {
			"cakephp/webmaster": "*"
		}
	}

And then update:

	php composer.phar update

That's it! You should now be ready to start configuring your channels.

### Submodule

	$ cd /app
	$ git submodule add git://github.com/gourmet/webmaster.git Plugin/Webmaster

### Clone

	$ cd /app/Plugin
	$ git clone git://github.com/gourmet/webmaster.git

## Configuration

You need to enable the plugin your `app/Config/bootstrap.php` file:

	CakePlugin::load('Webmaster', array('bootstrap' => true, 'routes' => true));

If you are already using `CakePlugin::loadAll();`, then this is not necessary.

## Usage

@todo write some usage example(s)

## Patches & Features

* Fork
* Mod, fix
* Test - this is important, so it's not unintentionally broken
* Commit - do not mess with license, todo, version, etc. (if you do change any, bump them into commits of their own that I can ignore when I pull)
* Pull request - bonus point for topic branches

## Bugs & Feedback

http://github.com/gourmet/webmaster/issues

## License

Copyright 2013, [Jad Bitar](http://jadb.io)

Licensed under [The MIT License](http://www.opensource.org/licenses/mit-license.php)<br/>
Redistributions of files must retain the above copyright notice.
