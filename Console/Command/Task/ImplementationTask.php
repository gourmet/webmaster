<?php
/**
 * Implementation Task
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
 * Implementation Task
 *
 * Check the application's implementation requirements.
 *
 * @package       Webmaster.Console.Command.Task
 */
class ImplementationTask extends CommonAppShell {

/**
 * Execution method always used for tasks
 *
 * @return void
 */
	public function execute() {
		if (!Configure::check('PHP')) {
			try {
				Configure::load('requirements', Common::read('Common.reader.id', 'default'));
			} catch (ConfigureException $e) {
				$this->out();
				$this->out(__d('webmaster', "No requirements defined. See %s for more info.", Common::wiki('requirements')), 2);
				return;
			}
		}

		$width = 20;
		$reqs = Configure::read('PHP');

		$this->hr(true);
		$this->out(__d('webmaster', "Checking system defined requirements:"));
		$this->hr(true);

		$this->out(__d('webmaster', "PHP Extensions:"));
		$this->out(__d('webmaster', "Whether or not required PHP extensions are loaded."));
		$this->hr(true);

		if (empty($reqs['extensions'])) {
			$reqs['extensions'] = array();
			$this->out(__d('webmaster', "None required."));
		}

		foreach ($reqs['extensions'] as $ext) {
			$out = $ext;
			$out .= str_repeat(' ', $width - strlen($ext));
			$out .= ':     ';
			if (!extension_loaded($ext)) {
				$out .= __d('webmaster', "Missing");
			} else {
				$out .= __d('webmaster', "Loaded");
			}
			$this->out($out);
		}
		unset($reqs['extensions']);

		$this->hr(true);

		$this->out(__d('webmaster', "PHP Runtime Settings:"));
		$this->out(__d('webmaster', "Whether or not required 'php.ini' directives are modifiable."));
		$this->hr(true);

		if (empty($reqs['runtime'])) {
			$reqs['runtime'] = array();
			$this->out(__d('webmaster', "None required."));
		} else {
			$all = ini_get_all();
		}

		foreach ($reqs['runtime'] as $varname) {
			$out = $varname;
			$out .= str_repeat(' ', $width - strlen($varname));
			$out .= ':     ';

			if (!isset($all[$varname])) {
				continue;
			}

			if ($all[$varname]['access'] < 6) {
				$out .= __d('webmaster', "No");
			} else {
				$out .= __d('webmaster', "Yes");
			}
			$this->out($out);
		}
		unset($reqs['runtime']);

		$this->hr(true);

		$this->out(__d('webmaster', "PHP Settings:"));
		$this->out(__d('webmaster', "Whether or not required 'php.ini' directives are correctly set."));
		$this->hr(true);

		if (empty($reqs)) {
			$this->out(__d('webmaster', "None required."));
		}

		foreach ($reqs as $var => $val) {
			$out = $var;
			$out .= str_repeat(' ', $width - strlen($var));
			$out .= ':     ';

			$ini = ini_get($var);

			if (is_bool($val)) {
				if ((bool) $ini !== $val) {
					$out .= __d(
						'webmaster',
						"Invalid (value: %s, expects: %s)",
						$ini, (int) $val
					);
				} else {
					$out .= __d('webmaster', "Valid (value: %s)", $ini);
				}
				continue;
			}

			if ($this->__inBytes($ini) < $this->__inBytes($val)) {
				$out .= __d(
					'webmaster',
					"Invalid (value: %s, expects: %s)",
					$ini, $val
				);
			} else {
				$out .= __d('webmaster', "Valid   (value: %s)", $ini);
			}

			$this->out($out);
		}

		$this->hr(true);
	}

/**
 * {@inheritdoc}
 */
	public function getOptionParser() {
		$parser = parent::getOptionParser();
		return $parser->description(__d('webmaster', "Check application's requirements"));
	}

/**
 * Transform string to bytes when possible, otherwise keep untouched.
 *
 * @param mixed $val Value to try modifying.
 * @return mixed
 */
	private function __inBytes($val) {
		$val = trim($val);

		switch (strtolower(substr($val, -1))) {
			case 'm':
				$val = (int)substr($val, 0, -1) * 1048576;
				break;
			case 'k':
				$val = (int)substr($val, 0, -1) * 1024;
				break;
			case 'g':
				$val = (int)substr($val, 0, -1) * 1073741824;
				break;
			case 'b':
				switch (strtolower(substr($val, -2, 1))) {
					case 'm':
						$val = (int)substr($val, 0, -2) * 1048576;
						break;
					case 'k':
						$val = (int)substr($val, 0, -2) * 1024;
						 break;
					case 'g':
						$val = (int)substr($val, 0, -2) * 1073741824;
						break;
					default :
				}
				break;
			default:
		}
		return $val;
	}

}
