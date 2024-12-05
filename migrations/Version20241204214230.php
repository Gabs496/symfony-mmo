<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241204214230 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_map_resource_spot DROP FOREIGN KEY FK_2D6D8BC053C55F64');
        $this->addSql('ALTER TABLE data_map_resource_spot DROP FOREIGN KEY FK_2D6D8BC06E9FCF67');
        $this->addSql('DROP TABLE data_map_resource_spot');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE data_map_resource_spot (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', map_id VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, map_resource_id VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, resource_quantity INT NOT NULL, spawned_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2D6D8BC053C55F64 (map_id), INDEX IDX_2D6D8BC06E9FCF67 (map_resource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE data_map_resource_spot ADD CONSTRAINT FK_2D6D8BC053C55F64 FOREIGN KEY (map_id) REFERENCES game_map (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_map_resource_spot ADD CONSTRAINT FK_2D6D8BC06E9FCF67 FOREIGN KEY (map_resource_id) REFERENCES game_map_resource (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
