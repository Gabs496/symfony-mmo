<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241204213213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE data_map_available_activity (id VARCHAR(255) NOT NULL, map_id VARCHAR(50) DEFAULT NULL, map_resource_id VARCHAR(50) DEFAULT NULL, type VARCHAR(255) NOT NULL, quantity DOUBLE PRECISION NOT NULL, INDEX IDX_4478C0053C55F64 (map_id), INDEX IDX_4478C006E9FCF67 (map_resource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE data_map_available_activity ADD CONSTRAINT FK_4478C0053C55F64 FOREIGN KEY (map_id) REFERENCES game_map (id)');
        $this->addSql('ALTER TABLE data_map_available_activity ADD CONSTRAINT FK_4478C006E9FCF67 FOREIGN KEY (map_resource_id) REFERENCES game_map_resource (id)');
        $this->addSql('ALTER TABLE game_resource ADD gathering_time DOUBLE PRECISION NOT NULL, CHANGE skill_needed mastery_involved VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_map_available_activity DROP FOREIGN KEY FK_4478C0053C55F64');
        $this->addSql('ALTER TABLE data_map_available_activity DROP FOREIGN KEY FK_4478C006E9FCF67');
        $this->addSql('DROP TABLE data_map_available_activity');
        $this->addSql('ALTER TABLE game_resource DROP gathering_time, CHANGE mastery_involved skill_needed VARCHAR(255) NOT NULL');
    }
}
