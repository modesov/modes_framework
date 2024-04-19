<?php

namespace Modes\Framework\Console\Commands;

use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Schema\Schema;
use Doctrine\DBAL\Types\Types;
use Modes\Framework\Console\CommandInterface;

class MigrationCommand extends AbstractCommand implements CommandInterface
{
    private string $name = "migrate";

    private const MIGRATIONS_TABLE_NAME = "migrations";

    public function __construct(
        private Connection $connection,
        private string     $migrationsPath,
    )
    {
    }

    protected array $help = [
        'help' => 'Выводит список доступных комманд с описанием',
        'send' => 'Может принимать значения email | sms. Пример --send=email отправит сообщение об успешной мигрции на email',
    ];

    public function execute(array $parameters = []): int
    {
        if (isset($parameters['help'])) {
            echo $this->getHelp($parameters);
            return 0;
        }

        echo $this->createMigrationsTable();

        try {
            $this->connection->beginTransaction();

            $appliedMigrations = $this->getAppliedMigrations();

            $migrationFiles = $this->getMigrationFiles();

            $migrationsToApply = array_values(array_diff($migrationFiles, $appliedMigrations));

            $schema = new Schema();

            foreach ($migrationsToApply as $migrationFile) {
                $migrationInstance = require $this->migrationsPath . "/$migrationFile";

                $migrationInstance->up($schema);

                $this->addMigration($migrationFile);
            }

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            foreach ($sqlArray as $sql) {
                $this->connection->executeQuery($sql);
            }

            $this->connection->commit();
        } catch (\Throwable $exception) {
            $this->connection->rollBack();

            throw $exception;
        }

        return 0;
    }

    private function createMigrationsTable(): string
    {
        $schemaManager = $this->connection->createSchemaManager();

        if (!$schemaManager->tablesExist([self::MIGRATIONS_TABLE_NAME])) {
            $schema = new Schema();
            $table = $schema->createTable(self::MIGRATIONS_TABLE_NAME);
            $table->addColumn('id', Types::INTEGER, ['unsigned' => true])->setAutoincrement(true);
            $table->addColumn('migration', Types::STRING);
            $table->addColumn('crated_at', Types::DATETIME_IMMUTABLE, [
                'default' => 'CURRENT_TIMESTAMP'
            ]);
            $table->setPrimaryKey(['id']);

            $sqlArray = $schema->toSql($this->connection->getDatabasePlatform());

            $this->connection->executeQuery($sqlArray[0]);

            return "Создана таблица " . self::MIGRATIONS_TABLE_NAME . PHP_EOL;
        }

        return "Таблица " . self::MIGRATIONS_TABLE_NAME . " уже существует" . PHP_EOL;
    }

    private function getAppliedMigrations(): array
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        return $queryBuilder
            ->select('migration')
            ->from(self::MIGRATIONS_TABLE_NAME)
            ->executeQuery()
            ->fetchFirstColumn();
    }

    private function getMigrationFiles(): array
    {
        $migrationFiles = scandir($this->migrationsPath);

        $filteredFiles = array_filter($migrationFiles, fn($fileName) => !in_array($fileName, ['.', '..']));

        return array_values($filteredFiles);
    }

    private function addMigration($migration)
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->insert(self::MIGRATIONS_TABLE_NAME)
            ->values(['migration' => ':migration'])
            ->setParameter('migration', $migration)
            ->executeQuery();
    }
}