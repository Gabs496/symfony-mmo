<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241128000124 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_map_resource_spot DROP FOREIGN KEY FK_2D6D8BC06E9FCF67');
        $this->addSql('ALTER TABLE data_map_resource_spot ADD CONSTRAINT FK_2D6D8BC06E9FCF67 FOREIGN KEY (map_resource_id) REFERENCES game_map_resource (id)');
        $this->addSql('ALTER TABLE game_map_resource DROP FOREIGN KEY FK_30B3F65F6E9FCF67');
        $this->addSql('DROP INDEX IDX_30B3F65F6E9FCF67 ON game_map_resource');
        $this->addSql('ALTER TABLE game_map_resource CHANGE map_resource_id resource_id VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE game_map_resource ADD CONSTRAINT FK_30B3F65F89329D25 FOREIGN KEY (resource_id) REFERENCES game_resource (id)');
        $this->addSql('CREATE INDEX IDX_30B3F65F89329D25 ON game_map_resource (resource_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_map_resource DROP FOREIGN KEY FK_30B3F65F89329D25');
        $this->addSql('DROP INDEX IDX_30B3F65F89329D25 ON game_map_resource');
        $this->addSql('ALTER TABLE game_map_resource CHANGE resource_id map_resource_id VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE game_map_resource ADD CONSTRAINT FK_30B3F65F6E9FCF67 FOREIGN KEY (map_resource_id) REFERENCES game_resource (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_30B3F65F6E9FCF67 ON game_map_resource (map_resource_id)');
        $this->addSql('ALTER TABLE data_map_resource_spot DROP FOREIGN KEY FK_2D6D8BC06E9FCF67');
        $this->addSql('ALTER TABLE data_map_resource_spot ADD CONSTRAINT FK_2D6D8BC06E9FCF67 FOREIGN KEY (map_resource_id) REFERENCES game_resource (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
