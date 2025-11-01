<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250805132726 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE game_game_object (id CHAR(36) NOT NULL COMMENT '(DC2Type:guid)', type VARCHAR(255) NOT NULL, components JSON NOT NULL COMMENT '(DC2Type:json_document)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_map_object ADD game_object_id CHAR(36) NOT NULL COMMENT '(DC2Type:guid)', DROP object_id, DROP components
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_map_object ADD CONSTRAINT FK_2F5B73DF18B9729 FOREIGN KEY (game_object_id) REFERENCES game_game_object (id)
        SQL);
        $this->addSql(<<<'SQL'
            CREATE UNIQUE INDEX UNIQ_2F5B73DF18B9729 ON game_map_object (game_object_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE game_map_object DROP FOREIGN KEY FK_2F5B73DF18B9729
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE game_game_object
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX UNIQ_2F5B73DF18B9729 ON game_map_object
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_map_object ADD object_id VARCHAR(50) NOT NULL, ADD components JSON NOT NULL COMMENT '(DC2Type:json_document)', DROP game_object_id
        SQL);
    }
}
