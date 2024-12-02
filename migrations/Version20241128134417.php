<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241128134417 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_resource ADD product_id VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE game_resource ADD CONSTRAINT FK_17D50B304584665A FOREIGN KEY (product_id) REFERENCES game_item (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_17D50B304584665A ON game_resource (product_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_resource DROP FOREIGN KEY FK_17D50B304584665A');
        $this->addSql('DROP INDEX UNIQ_17D50B304584665A ON game_resource');
        $this->addSql('ALTER TABLE game_resource DROP product_id');
    }
}
