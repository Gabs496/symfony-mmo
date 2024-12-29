<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241228221822 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_map_resource DROP FOREIGN KEY FK_30B3F65F89329D25');
        $this->addSql('DROP INDEX IDX_30B3F65F89329D25 ON game_map_resource');
        $this->addSql('ALTER TABLE game_map_resource CHANGE resource_id resource_id VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_map_resource CHANGE resource_id resource_id VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE game_map_resource ADD CONSTRAINT FK_30B3F65F89329D25 FOREIGN KEY (resource_id) REFERENCES game_resource (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_30B3F65F89329D25 ON game_map_resource (resource_id)');
    }
}
