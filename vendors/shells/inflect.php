<?php
/**
 * Inflect Shell
 *
 * Inflect the heck out of your word(s)
 *
 * @category Shell
 * @package  Subway
 * @version  0.2
 * @author   Jose Diaz-Gonzalez
 * @license  http://www.opensource.org/licenses/mit-license.php The MIT License
 * @link     http://www.josediazgonzalez.com
 */
class InflectShell extends Shell {
/**
 * valid inflection rules
 *
 * @var string
 **/
	var $validMethods = array(
		'pluralize', 'singularize', 'camelize',
		'underscore', 'humanize', 'tableize',
		'classify', 'variable', 'slug'
	);

/**
 * valid inflection rules
 *
 * @var string
 **/
	var $validCommands = array(
		'pluralize', 'singularize', 'camelize',
		'underscore', 'humanize', 'tableize',
		'classify', 'variable', 'slug', 'all', 'quit'
	);


/**
 * Inflects words
 *
 * @return void
 * @access public
 */
	function main() {
		if (!empty($this->args)) {
			$arguments = $this->__parseArguments($this->args);
		} else {
			$arguments = $this->__interactive();
		}
		$this->__inflect($arguments['method'], $arguments['words']);
	}

/**
 * Prompts the user for words
 *
 * @return array
 * @author Jose Diaz-Gonzalez
 **/
	function __interactive() {
		$method = $this->__getMethod();
		$words = $this->__getInput();
		return array('method' => $method, 'words' => $words);
	}

/**
 * Requests a valid inflection method
 *
 * @return void
 * @author Jose Diaz-Gonzalez
 **/
	function __getMethod() {
		$validCharacters = array('1', '2', '3', '4', '5', '6', '7', '8', '9', 'q');
		$validCommands = array_merge($validCharacters, $this->validCommands);

		$command = null;
		while (empty($command)) {
			$this->out("Please type the number or name of the inflection method you would like to use");
			$this->hr();
			$this->out("[1] Pluralize");
			$this->out("[2] Singularize");
			$this->out("[3] Camelize");
			$this->out("[4] Underscore");
			$this->out("[5] Humanize");
			$this->out("[6] Tableize");
			$this->out("[7] Classify");
			$this->out("[8] Variable");
			$this->out("[9] Slug");
			$this->out("[q] Quit");
			$temp = $this->in("What command would you like to perform?", null, 'q');
			if (in_array(strtolower($temp), $validCommands)) {
				$command = strtolower($temp);
			} else {
				$this->out("Try again.");
			}
		}

		switch ($command) {
			case '1' :
			case 'pluralize' :
				return 'pluralize';
				break;
			case '2' :
			case 'singularize' :
				return 'singularize';
				break;
			case '3' :
			case 'camelize' :
				return 'camelize';
				break;
			case '4' :
			case 'underscore' :
				return 'underscore';
				break;
			case '5' :
			case 'humanize' :
				return 'humanize';
				break;
			case '6' :
			case 'tableize' :
				return 'tableize';
				break;
			case '7' :
			case 'classify' :
				return 'classify';
				break;
			case '8' :
			case 'variable' :
				return 'variable';
			case '9' :
			case 'slug' :
				return 'slug';
				$this->_stop();
			case 'q' :
			case 'quit' :
			default :
				$this->out(__("Exit", true));
				$this->_stop();
				break;
		}
	}

/**
 * Requests words to inflect
 *
 * @return array
 * @author Jose Diaz-Gonzalez
 **/
	function __getInput() {
		$words = null;
		while (empty($words)) {
			$temp = $this->in("What word(s) would you like to inflect?");
			if (!empty($temp)) {
				$words = $temp;
			} else {
				$this->out("Try again.");
			}
		}
		return $words;
	}

/**
 * Parse the arguments into the function and the word(s) to be inflected
 *
 * @return array
 * @author Jose Diaz-Gonzalez
 **/
	function __parseArguments($arguments) {
		$words = null;
		$function = $arguments[0];
		unset($arguments[0]);
		if (!in_array($function, array_merge($this->validMethods, array('all')))) {
			$function = $this->__getMethod();
		}

		$arguments = array_reverse($arguments);
		if (count($arguments) == 0) {
			$words = $this->__getInput();
		} else {
			while (count($arguments) > 0) {
				$words .= array_pop($arguments);
				if (count($arguments) > 0) {
					$words .= " ";
				}
			}
		}

		return array('method' => $function, 'words' => $words);
	}

/**
 * Inflects a set of words based upon the inflection set in the arguments
 *
 * @return void
 * @author savant
 **/
	function __inflect($function, $words) {
		$this->out($words);
		if ($function === 'all') {
			foreach ($this->validMethods as $method) {
				$functionName = $this->__getMessage($method);
				$this->out("{$functionName}: " . Inflector::$method($words));
			}
		} else {
			$functionName = $this->__getMessage($function);
			$this->out("{$functionName}: " . Inflector::$function($words));
		}
	}

/**
 * Returns the appropriate message for a given function
 *
 * @return void
 * @author savant
 **/
	function __getMessage($function) {
		$messages = array(
			'camelize' => 		'CamelCase form             ',
			'classify' => 		'Cake Model Class form      ',
			'humanize' => 		'Human Readable Group form  ',
			'singularize' =>	'Singular form              ',
			'slug' => 			'Slugged_form               ',
			'pluralize' => 		'Pluralized form            ',
			'tableize' => 		'table_names form           ',
			'underscore' => 	'under_scored_form          ',
			'variable' => 		'variableForm               '
		);
		return $messages[$function];
	}

/**
 * Displays help contents
 *
 * @return void
 * @access public
 */
	function help() {
		$this->out('Inflector Shell - http://josediazgonzalez.com');
		$this->out('');
		$this->out('This shell uses the Inflector class to inflect any word(s) you wish');
		$this->hr();
		$this->out("Usage: cake inflect");
		$this->out("       cake inflect methodName");
		$this->out("       cake inflect methodName word");
		$this->out("       cake inflect methodName words to inflect");
		$this->out('');
	}
}
?>