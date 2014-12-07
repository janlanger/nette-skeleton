<?php

require __DIR__ . '/../vendor/autoload.php';

umask(0);

$configurator = new Nette\Configurator;

//$configurator->setDebugMode(FALSE); //debug mode MUST NOT be enabled on production. Default is autodetection
$configurator->enableDebugger(__DIR__ . '/../log');

$configurator->setTempDirectory(__DIR__ . '/../temp');

$configurator->createRobotLoader()
	->addDirectory(__DIR__)
	->register();

$configurator->addConfig(__DIR__ . '/config/config.neon');
$configurator->addConfig(__DIR__ . '/config/config.local.neon');
$container = $configurator->createContainer();

return $container;
