<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250316043113 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_map_available_activity DROP FOREIGN KEY FK_4478C006E9FCF67');
        $this->addSql('ALTER TABLE data_map_available_activity DROP FOREIGN KEY FK_4478C009477FFAB');
        $this->addSql('DROP TABLE game_map_resource');
        $this->addSql('DROP TABLE data_map_available_activity');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_map_resource (id VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, resource_id VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, map_id VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, max_global_availability INT NOT NULL, max_spot_availability INT NOT NULL, spot_spawn_frequency INT NOT NULL, UNIQUE INDEX UNIQ_30B3F65F89329D2553C55F64 (resource_id, map_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE data_map_available_activity (id VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, map_resource_id VARCHAR(50) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, involving_activity_id CHAR(36) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', map_id VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, type VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, quantity DOUBLE PRECISION NOT NULL, icon VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, name VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, INDEX IDX_4478C009477FFAB (involving_activity_id), INDEX IDX_4478C006E9FCF67 (map_resource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE data_map_available_activity ADD CONSTRAINT FK_4478C006E9FCF67 FOREIGN KEY (map_resource_id) REFERENCES game_map_resource (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_map_available_activity ADD CONSTRAINT FK_4478C009477FFAB FOREIGN KEY (involving_activity_id) REFERENCES data_activity (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
