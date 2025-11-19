<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251119105633 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B1233F14CB4F');
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B12331009DBE');
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B123517FE9FE');
        $this->addSql('ALTER TABLE data_activity RENAME activity_activity ');
        $this->addSql('ALTER TABLE game_game_object RENAME core_game_object ');
        $this->addSql('ALTER TABLE data_item_bag RENAME item_item_bag');
        $this->addSql('ALTER TABLE data_item_object RENAME item_item_object');
        $this->addSql('ALTER TABLE game_map_object RENAME map_map_object');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B12331009DBE FOREIGN KEY (backpack_id) REFERENCES item_item_bag (id)');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B1233F14CB4F FOREIGN KEY (current_activity_id) REFERENCES activity_activity (id)');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B123517FE9FE FOREIGN KEY (equipment_id) REFERENCES item_item_bag (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B1233F14CB4F');
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B12331009DBE');
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B123517FE9FE');
        $this->addSql('CREATE TABLE data_activity (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', type VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, started_at DOUBLE PRECISION DEFAULT NULL, duration DOUBLE PRECISION NOT NULL, scheduled_at DOUBLE PRECISION NOT NULL, completed_at DOUBLE PRECISION DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE data_item_bag (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', type VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, size DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE game_map_object (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', map_id VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, spawned_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_2F5B73D53C55F64 (map_id), UNIQUE INDEX UNIQ_2F5B73DF18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE data_item_object (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', bag_id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', INDEX IDX_D19481696F5D8297 (bag_id), UNIQUE INDEX UNIQ_D1948169F18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE game_game_object (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', type VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, components JSON NOT NULL COMMENT \'(DC2Type:json_document)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE game_map_object ADD CONSTRAINT FK_2F5B73DF18B9729 FOREIGN KEY (game_object_id) REFERENCES game_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_item_object ADD CONSTRAINT FK_D19481696F5D8297 FOREIGN KEY (bag_id) REFERENCES data_item_bag (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_item_object ADD CONSTRAINT FK_D1948169F18B9729 FOREIGN KEY (game_object_id) REFERENCES game_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE item_item_object DROP FOREIGN KEY FK_CE3C8615F18B9729');
        $this->addSql('ALTER TABLE item_item_object DROP FOREIGN KEY FK_CE3C86156F5D8297');
        $this->addSql('ALTER TABLE map_map_object DROP FOREIGN KEY FK_F95635DEF18B9729');
        $this->addSql('DROP TABLE activity_activity');
        $this->addSql('DROP TABLE core_game_object');
        $this->addSql('DROP TABLE item_item_bag');
        $this->addSql('DROP TABLE item_item_object');
        $this->addSql('DROP TABLE map_map_object');
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B12331009DBE');
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B123517FE9FE');
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B1233F14CB4F');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B12331009DBE FOREIGN KEY (backpack_id) REFERENCES data_item_bag (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B123517FE9FE FOREIGN KEY (equipment_id) REFERENCES data_item_bag (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B1233F14CB4F FOREIGN KEY (current_activity_id) REFERENCES data_activity (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
