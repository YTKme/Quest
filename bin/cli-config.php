<?php

use Symfony\Component\Console\Application;

$cli = new Application('Doctrine Command Line Interface', \Doctrine\ORM\Version::VERSION);
$app = require __DIR__ . '/../src/quest/quest.php';

$cli->setHelperSet(new \Symfony\Component\Console\Helper\HelperSet(array(
	'db' => new \Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper($app['db']),
	'dialog' => new \Symfony\Component\Console\Helper\DialogHelper(),
	'em' => new \Doctrine\ORM\Tools\Console\Helper\EntityManagerHelper($app['quest.orm.manager'])
)));

$cli->addCommands(array(
	// Migration Command
	new \Doctrine\DBAL\Migrations\Tools\Console\Command\DiffCommand(),
	new \Doctrine\DBAL\Migrations\Tools\Console\Command\ExecuteCommand(),
	new \Doctrine\DBAL\Migrations\Tools\Console\Command\GenerateCommand(),
	new \Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand(),
	new \Doctrine\DBAL\Migrations\Tools\Console\Command\StatusCommand(),
	new \Doctrine\DBAL\Migrations\Tools\Console\Command\VersionCommand(),
	
	// DBAL Command
	new \Doctrine\DBAL\Tools\Console\Command\ImportCommand(),
	new \Doctrine\DBAL\Tools\Console\Command\RunSqlCommand(),
	
	// ORM Command
	new \Doctrine\ORM\Tools\Console\Command\ClearCache\MetadataCommand(),
	new \Doctrine\ORM\Tools\Console\Command\ClearCache\QueryCommand(),
	new \Doctrine\ORM\Tools\Console\Command\ClearCache\ResultCommand(),
	new \Doctrine\ORM\Tools\Console\Command\ConvertDoctrine1SchemaCommand(),
	new \Doctrine\ORM\Tools\Console\Command\ConvertMappingCommand(),
	new \Doctrine\ORM\Tools\Console\Command\EnsureProductionSettingsCommand(),
	new \Doctrine\ORM\Tools\Console\Command\GenerateEntitiesCommand(),
	new \Doctrine\ORM\Tools\Console\Command\GenerateProxiesCommand(),
	new \Doctrine\ORM\Tools\Console\Command\GenerateRepositoriesCommand(),
	new \Doctrine\ORM\Tools\Console\Command\InfoCommand(),
	new \Doctrine\ORM\Tools\Console\Command\RunDqlCommand(),
	new \Doctrine\ORM\Tools\Console\Command\SchemaTool\CreateCommand(),
	new \Doctrine\ORM\Tools\Console\Command\SchemaTool\DropCommand(),
	new \Doctrine\ORM\Tools\Console\Command\SchemaTool\UpdateCommand(),
	new \Doctrine\ORM\Tools\Console\Command\ValidateSchemaCommand()
));

$cli->run();