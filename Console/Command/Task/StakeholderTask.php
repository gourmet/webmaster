<?php
/**
 * Stakeholder Task
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

/**
 * Stakeholder Task
 *
 * Checks the application's stakeholder conditions.
 *
 * @package       Webmaster.Console.Command.Task
 */
class StakeholderTask extends CommonAppShell {

/**
 * Execution method always used for tasks
 *
 * @return void
 */
	public function execute() {
		$this->out();
		$this->out(__d('webmaster', "<error>Not implemented yet.</error>"));
		$this->out();
	}

/**
 * {@inheritdoc}
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		return $parser->description(__d('webmaster', "Check application's stakeholder conditions."));
	}

}
