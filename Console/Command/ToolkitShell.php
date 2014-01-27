<?php
/**
 * Toolkit Shell
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

App::uses('InteractiveAppShell', 'Common.Console');

/**
 * Toolkit Shell
 *
 * @package       Webmaster.Console.Command
 */
class ToolkitShell extends InteractiveAppShell {

/**
 * {@inheritdoc}
 */
	public $tasks = array(
		'Webmaster.ClearCache',
		'Webmaster.Requirements',
	);

/**
 * {@inheritdoc}
 */
	private $__options = array(
		'C' => 'ClearCache',
		'H' => 'password',
		'R' => 'Requirements',
		'Q' => 'quit'
	);

/**
 * {@inheritdoc}
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		return $parser->description(__d('webmaster',
			"Webmaster Toolkit"
		))->addSubcommand('clear_cache', array(
			'help' => __d('webmaster', "Clear application's cache"),
			'parser' => $this->ClearCache->getOptionParser(),
		))->addSubcommand('password', array(
			'help' => __d('webmaster', "Get hashed password"),
			'parser' => array(
				'description' => __d('webmaster', "Get hashed password"),
				'arguments' => array(
					'password' => array(
						'help' => __d('webmaster', "Password to hash"),
					),
				),
			)
		))->addSubcommand('requirements', array(
			'help' => __d('webmaster', "Check the application's requirements"),
			'parser' => $this->Requirements->getOptionParser()
		));
	}

/**
 * {@inheritdoc}
 */
	public function main() {
		$this->out(__d('webmaster', "Interactive Toolkit Shell"));
		$this->hr();
		$this->out(__d('webmaster', "[C]lear cache"));
		$this->out(__d('webmaster', "[H]ash password"));
		$this->out(__d('webmaster', "[R]equirements"));
		$this->out(__d('webmaster', "[Q]uit"));

		$option = $this->in(__d('webmaster', "What would you like to do?"), array_keys($this->__options));

		$method = $this->__options[strtoupper($option)];
		if ($this->hasMethod($method)) {
			$this->{$method}();
		} else if ($this->hasTask($method)) {
			$this->{$method}->runCommand('execute', $this->args);
		}
		$this->main();
	}

/**
 * Get hashed password
 *
 * Usage: ./Console/cake webmaster.toolkit password myPasswordHere
 */
	public function password() {
		if (empty($this->args[0])) {
			$this->args[0] = $this->in(__d('webmaster', "What would you like to hash?"));
		}

		$value = trim($this->args['0']);

		$this->out();
		$this->out(__d('webmaster', "Hashed password: %s", Security::hash($value, null, true)));
		$this->out();
	}

}
