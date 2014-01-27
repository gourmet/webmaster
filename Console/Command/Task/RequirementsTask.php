<?php
/**
 * Requirements Task
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
 * Requirements Task
 *
 * @package       Webmaster.Console.Command.Task
 */
class RequirementsTask extends InteractiveAppShell {

/**
 * {@inheritdoc}
 */
	public $tasks = array(
		'Webmaster.Availability',
		'Webmaster.Implementation',
		'Webmaster.Stakeholder'
	);

/**
 * {@inheritdoc}
 */
	private $__options = array(
		'A' => 'Availability',
		'I' => 'Implementation',
		'S' => 'Stakeholder',
		'Q' => 'quit'
	);

/**
 * Execution method always used for tasks
 *
 * @return void
 */
	public function execute() {
		if (!empty($this->args)) {
			$command = Inflector::classify(array_shift($this->args));
			if ($this->hasTask($command)) {
				return $this->{$command}->runCommand('execute', $this->args);
			}
		}

		$this->out(__d('webmaster', "Interactive Requirements Shell"));
		$this->hr();
		$this->out(__d('webmaster', "[A]vailability"));
		$this->out(__d('webmaster', "[I]mplementation"));
		$this->out(__d('webmaster', "[S]takeholder"));
		$this->out(__d('webmaster', "[Q]uit"));

		$option = $this->in(__d('webmaster', "What would you like to do?"), array_keys($this->__options));

		$method = $this->__options[strtoupper($option)];
		if ($this->hasMethod($method)) {
			$this->{$method}();
		} else if ($this->hasTask($method)) {
			$this->{$method}->runCommand('execute', $this->args);
		}
		$this->execute();
	}

/**
 * {@inheritdoc}
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		return $parser->description(__d('webmaster',
			"Check the application's requirements"
		))->addSubcommand('availability', array(
			'help' => __d('webmaster', "Check application's services availability"),
			'parser' => $this->Availability->getOptionParser()
		))->addSubcommand('implementation', array(
			'help' => __d('webmaster', "Check application's implementation requirements"),
			'parser' => $this->Implementation->getOptionParser()
		))->addSubcommand('stakeholder', array(
			'help' => __d('webmaster', "Check application's stakeholder conditions"),
			'parser' => $this->Stakeholder->getOptionParser()
		));
	}

}
