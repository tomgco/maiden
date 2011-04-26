<?php
/**
 * CLI entry point for Maiden
 *
 * @author Paul Serby <paul.serby@clock.co.uk>
 * @license http://opensource.org/licenses/bsd-license.php New BSD License
 */

require_once "MaidenRunner.php";
require_once "lib/Logger.php";

$maidenRunner = new MaidenRunner($logger = new Logger(Logger::LEVEL_INFO));

// Commandline options
$options = array(
	"-l" => function($args) use ($maidenRunner) {
		$maidenRunner->listTargets();
		return false;
	},
	"-v" => function($args) use ($maidenRunner, $logger) {
		$logger->setLevel(Logger::LEVEL_DEBUG);
	}
);

array_shift($argv);

if (count($argv) == 0) {
	$argv[] = "-l";
}

$arguments = array();

foreach ($argv as $arg) {
	if (!isset($target) && isset($options[$arg])) {
		if ($options[$arg]($argv) === false) {
			exit;
		}
	} else {
		if (isset($target)) {
			$arguments[] = $arg;
		} else {
			$target = $arg;
		}
	}
}
// Run the chosen target
$maidenRunner->run($target, $arguments);
