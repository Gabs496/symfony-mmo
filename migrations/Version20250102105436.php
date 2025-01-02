<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250102105436 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_item_instance DROP FOREIGN KEY FK_DF7F6D3F126F525E');
        $this->addSql('DROP INDEX IDX_DF7F6D3F126F525E ON data_item_instance');
        $this->addSql('ALTER TABLE data_item_instance CHANGE item_id item_id VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_item_instance CHANGE item_id item_id VARCHAR(50) DEFAULT NULL');
        $this->addSql('ALTER TABLE data_item_instance ADD CONSTRAINT FK_DF7F6D3F126F525E FOREIGN KEY (item_id) REFERENCES game_item (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_DF7F6D3F126F525E ON data_item_instance (item_id)');
    }
}
