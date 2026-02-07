<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260117010041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B123517FE9FE');
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B12331009DBE');
        $this->addSql('CREATE TABLE equipment_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', stats JSON NOT NULL, UNIQUE INDEX UNIQ_9BC3E72EF18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipment_set_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', slots JSON NOT NULL, UNIQUE INDEX UNIQ_71BDE1BFF18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_bag_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', size DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_A0874AACF18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE place_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_3D65282AF18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE position_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', place_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', position VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_1CDFA02AF18B9729 (game_object_id), INDEX IDX_1CDFA02ADA6A219 (place_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipment_component ADD CONSTRAINT FK_9BC3E72EF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE equipment_set_component ADD CONSTRAINT FK_71BDE1BFF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE item_bag_component ADD CONSTRAINT FK_A0874AACF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE place_component ADD CONSTRAINT FK_3D65282AF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE position_component ADD CONSTRAINT FK_1CDFA02AF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE position_component ADD CONSTRAINT FK_1CDFA02ADA6A219 FOREIGN KEY (place_id) REFERENCES place_component (id)');
        $this->addSql('ALTER TABLE item_equipment_component DROP FOREIGN KEY FK_42850ECF18B9729');
        $this->addSql('ALTER TABLE item_item_object DROP FOREIGN KEY FK_D1948169F18B9729');
        $this->addSql('ALTER TABLE item_item_object DROP FOREIGN KEY FK_D19481696F5D8297');
        $this->addSql('ALTER TABLE map_component DROP FOREIGN KEY FK_EC96E60AF18B9729');
        $this->addSql('DROP TABLE item_equipment_component');
        $this->addSql('DROP TABLE item_item_bag');
        $this->addSql('DROP TABLE item_item_object');
        $this->addSql('DROP TABLE map_component');
        $this->addSql('DROP INDEX UNIQ_B0D0B12331009DBE ON data_player_character');
        $this->addSql('DROP INDEX UNIQ_B0D0B123517FE9FE ON data_player_character');
        $this->addSql('ALTER TABLE data_player_character DROP backpack_id, DROP equipment_id, DROP position, CHANGE game_object_id game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_equipment_component (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', stats JSON NOT NULL, UNIQUE INDEX UNIQ_42850ECF18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE item_item_bag (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', type VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, size DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE item_item_object (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', bag_id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', UNIQUE INDEX UNIQ_CE3C8615F18B9729 (game_object_id), INDEX IDX_CE3C86156F5D8297 (bag_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE map_component (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', coordinate_x DOUBLE PRECISION NOT NULL, coordinate_y DOUBLE PRECISION NOT NULL, spawns JSON NOT NULL, UNIQUE INDEX UNIQ_EC96E60AF18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE item_equipment_component ADD CONSTRAINT FK_42850ECF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE item_item_object ADD CONSTRAINT FK_D1948169F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE item_item_object ADD CONSTRAINT FK_D19481696F5D8297 FOREIGN KEY (bag_id) REFERENCES item_item_bag (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE map_component ADD CONSTRAINT FK_EC96E60AF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE equipment_component DROP FOREIGN KEY FK_9BC3E72EF18B9729');
        $this->addSql('ALTER TABLE equipment_set_component DROP FOREIGN KEY FK_71BDE1BFF18B9729');
        $this->addSql('ALTER TABLE item_bag_component DROP FOREIGN KEY FK_A0874AACF18B9729');
        $this->addSql('ALTER TABLE place_component DROP FOREIGN KEY FK_3D65282AF18B9729');
        $this->addSql('ALTER TABLE position_component DROP FOREIGN KEY FK_1CDFA02AF18B9729');
        $this->addSql('ALTER TABLE position_component DROP FOREIGN KEY FK_1CDFA02ADA6A219');
        $this->addSql('DROP TABLE equipment_component');
        $this->addSql('DROP TABLE equipment_set_component');
        $this->addSql('DROP TABLE item_bag_component');
        $this->addSql('DROP TABLE place_component');
        $this->addSql('DROP TABLE position_component');
        $this->addSql('ALTER TABLE data_player_character ADD backpack_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', ADD equipment_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', ADD position VARCHAR(50) DEFAULT NULL, CHANGE game_object_id game_object_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B123517FE9FE FOREIGN KEY (equipment_id) REFERENCES item_item_bag (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B12331009DBE FOREIGN KEY (backpack_id) REFERENCES item_item_bag (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B0D0B12331009DBE ON data_player_character (backpack_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B0D0B123517FE9FE ON data_player_character (equipment_id)');
    }
}
