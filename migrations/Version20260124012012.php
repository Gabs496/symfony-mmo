<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260124012012 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE map_object_spawn (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', map_component_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', prototype_id VARCHAR(50) NOT NULL, max_availability INT NOT NULL, spawn_rate DOUBLE PRECISION NOT NULL, dtype VARCHAR(255) NOT NULL, INDEX IDX_8C1816EB7A9E2211 (map_component_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE map_resource_spawn (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', min_spot_availability INT NOT NULL, max_spot_availability INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE map_object_spawn ADD CONSTRAINT FK_8C1816EB7A9E2211 FOREIGN KEY (map_component_id) REFERENCES map_component (id)');
        $this->addSql('ALTER TABLE map_resource_spawn ADD CONSTRAINT FK_E8410C1FBF396750 FOREIGN KEY (id) REFERENCES map_object_spawn (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE map_object_spawn DROP FOREIGN KEY FK_8C1816EB7A9E2211');
        $this->addSql('ALTER TABLE map_resource_spawn DROP FOREIGN KEY FK_E8410C1FBF396750');
        $this->addSql('DROP TABLE map_object_spawn');
        $this->addSql('DROP TABLE map_resource_spawn');
    }
}
