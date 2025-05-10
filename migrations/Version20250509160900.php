<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250509160900 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_item_instance DROP type, DROP wear, CHANGE properties components JSON NOT NULL COMMENT \'(DC2Type:json_document)\', CHANGE item_id item_prototype_id VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_item_instance ADD type VARCHAR(255) NOT NULL, ADD wear DOUBLE PRECISION NOT NULL, CHANGE item_prototype_id item_id VARCHAR(50) NOT NULL, CHANGE components properties JSON NOT NULL COMMENT \'(DC2Type:json_document)\'');
    }
}
