<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250313095154 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_activity ADD duration DOUBLE PRECISION NOT NULL, ADD scheduled_at DOUBLE PRECISION NOT NULL, ADD completed_at DOUBLE PRECISION DEFAULT NULL, DROP steps, CHANGE started_at started_at DOUBLE PRECISION DEFAULT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_activity ADD steps JSON NOT NULL COMMENT \'(DC2Type:json_document)\', DROP duration, DROP scheduled_at, DROP completed_at, CHANGE started_at started_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\'');
    }
}
