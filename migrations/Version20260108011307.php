<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260108011307 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attached_resource_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', max_avaliability INT NOT NULL, availability INT NOT NULL, UNIQUE INDEX UNIQ_CEB14491F18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE healing_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', amount DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_94EDBD94F18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', weight DOUBLE PRECISION NOT NULL, max_stack_size INT NOT NULL, quantity INT NOT NULL, UNIQUE INDEX UNIQ_4524B7A7F18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_equipment_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', stats JSON NOT NULL, UNIQUE INDEX UNIQ_42850ECF18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE map_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', coordinate_x DOUBLE PRECISION NOT NULL, coordinate_y DOUBLE PRECISION NOT NULL, spawns JSON NOT NULL, UNIQUE INDEX UNIQ_EC96E60AF18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE render_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(50) NOT NULL, description VARCHAR(255) NOT NULL, icon_path VARCHAR(50) NOT NULL, UNIQUE INDEX UNIQ_BD9FD430F18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE resource_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', gathering_difficulty DOUBLE PRECISION NOT NULL, involved_mastery VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_E137F2C8F18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE attached_resource_component ADD CONSTRAINT FK_CEB14491F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE healing_component ADD CONSTRAINT FK_94EDBD94F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE item_component ADD CONSTRAINT FK_4524B7A7F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE item_equipment_component ADD CONSTRAINT FK_42850ECF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE map_component ADD CONSTRAINT FK_EC96E60AF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE render_component ADD CONSTRAINT FK_BD9FD430F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE resource_component ADD CONSTRAINT FK_E137F2C8F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE attached_resource_component DROP FOREIGN KEY FK_CEB14491F18B9729');
        $this->addSql('ALTER TABLE healing_component DROP FOREIGN KEY FK_94EDBD94F18B9729');
        $this->addSql('ALTER TABLE item_component DROP FOREIGN KEY FK_4524B7A7F18B9729');
        $this->addSql('ALTER TABLE item_equipment_component DROP FOREIGN KEY FK_42850ECF18B9729');
        $this->addSql('ALTER TABLE map_component DROP FOREIGN KEY FK_EC96E60AF18B9729');
        $this->addSql('ALTER TABLE render_component DROP FOREIGN KEY FK_BD9FD430F18B9729');
        $this->addSql('ALTER TABLE resource_component DROP FOREIGN KEY FK_E137F2C8F18B9729');
        $this->addSql('DROP TABLE attached_resource_component');
        $this->addSql('DROP TABLE healing_component');
        $this->addSql('DROP TABLE item_component');
        $this->addSql('DROP TABLE item_equipment_component');
        $this->addSql('DROP TABLE map_component');
        $this->addSql('DROP TABLE render_component');
        $this->addSql('DROP TABLE resource_component');
    }
}
