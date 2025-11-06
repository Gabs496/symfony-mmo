<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251103092048 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE data_item_instance DROP FOREIGN KEY FK_DF7F6D3F6F5D8297
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE data_item_instance
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE data_item_instance (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT '(DC2Type:guid)', bag_id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT '(DC2Type:guid)', item_prototype_id VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, quantity INT NOT NULL, components JSON NOT NULL COMMENT '(DC2Type:json_document)', INDEX IDX_DF7F6D3F6F5D8297 (bag_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = '' 
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE data_item_instance ADD CONSTRAINT FK_DF7F6D3F6F5D8297 FOREIGN KEY (bag_id) REFERENCES data_item_bag (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
    }
}
