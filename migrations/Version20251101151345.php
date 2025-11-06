<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251101151345 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE data_item_object (id INT AUTO_INCREMENT NOT NULL, bag_id CHAR(36) NOT NULL COMMENT '(DC2Type:guid)', game_object_id CHAR(36) NOT NULL COMMENT '(DC2Type:guid)', INDEX IDX_D19481696F5D8297 (bag_id), UNIQUE INDEX UNIQ_D1948169F18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE data_item_object ADD CONSTRAINT FK_D19481696F5D8297 FOREIGN KEY (bag_id) REFERENCES data_item_bag (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE data_item_object ADD CONSTRAINT FK_D1948169F18B9729 FOREIGN KEY (game_object_id) REFERENCES game_game_object (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE data_item_instance CHANGE bag_id bag_id CHAR(36) NOT NULL COMMENT '(DC2Type:guid)'
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE data_item_object DROP FOREIGN KEY FK_D19481696F5D8297
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE data_item_object DROP FOREIGN KEY FK_D1948169F18B9729
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE data_item_object
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE data_item_instance CHANGE bag_id bag_id CHAR(36) DEFAULT NULL COMMENT '(DC2Type:guid)'
        SQL);
    }
}
