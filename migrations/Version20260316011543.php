<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260316011543 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE character_component DROP FOREIGN KEY FK_CEAE036CF18B9729');
        $this->addSql('ALTER TABLE character_component ADD CONSTRAINT FK_CEAE036CF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE combat_component DROP FOREIGN KEY FK_A5E80D13F18B9729');
        $this->addSql('ALTER TABLE combat_component ADD CONSTRAINT FK_A5E80D13F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B123F18B9729');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B123F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipment_component DROP FOREIGN KEY FK_9BC3E72EF18B9729');
        $this->addSql('ALTER TABLE equipment_component ADD CONSTRAINT FK_9BC3E72EF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipment_set_component DROP FOREIGN KEY FK_71BDE1BFF18B9729');
        $this->addSql('ALTER TABLE equipment_set_component ADD CONSTRAINT FK_71BDE1BFF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipped_component DROP FOREIGN KEY FK_F1C40616F18B9729');
        $this->addSql('ALTER TABLE equipped_component ADD CONSTRAINT FK_F1C40616F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE healing_component DROP FOREIGN KEY FK_94EDBD94F18B9729');
        $this->addSql('ALTER TABLE healing_component ADD CONSTRAINT FK_94EDBD94F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_bag_component DROP FOREIGN KEY FK_A0874AACF18B9729');
        $this->addSql('ALTER TABLE item_bag_component ADD CONSTRAINT FK_A0874AACF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_component DROP FOREIGN KEY FK_4524B7A7F18B9729');
        $this->addSql('ALTER TABLE item_component ADD CONSTRAINT FK_4524B7A7F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_in_bag_slot_component DROP FOREIGN KEY FK_E682F318F18B9729');
        $this->addSql('ALTER TABLE item_in_bag_slot_component ADD CONSTRAINT FK_E682F318F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE map_component DROP FOREIGN KEY FK_EC96E60AF18B9729');
        $this->addSql('ALTER TABLE map_component ADD CONSTRAINT FK_EC96E60AF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE map_in_component DROP FOREIGN KEY FK_B351C81AF18B9729');
        $this->addSql('ALTER TABLE map_in_component ADD CONSTRAINT FK_B351C81AF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE render_component DROP FOREIGN KEY FK_BD9FD430F18B9729');
        $this->addSql('ALTER TABLE render_component ADD CONSTRAINT FK_BD9FD430F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resource_attached_component DROP FOREIGN KEY FK_D10BB072F18B9729');
        $this->addSql('ALTER TABLE resource_attached_component ADD CONSTRAINT FK_F9BB3C9BF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE resource_attached_component RENAME INDEX uniq_d10bb072f18b9729 TO UNIQ_F9BB3C9BF18B9729');
        $this->addSql('ALTER TABLE resource_component DROP FOREIGN KEY FK_E137F2C8F18B9729');
        $this->addSql('ALTER TABLE resource_component ADD CONSTRAINT FK_E137F2C8F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B123F18B9729');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B123F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE equipped_component DROP FOREIGN KEY FK_F1C40616F18B9729');
        $this->addSql('ALTER TABLE equipped_component ADD CONSTRAINT FK_F1C40616F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE equipment_set_component DROP FOREIGN KEY FK_71BDE1BFF18B9729');
        $this->addSql('ALTER TABLE equipment_set_component ADD CONSTRAINT FK_71BDE1BFF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE healing_component DROP FOREIGN KEY FK_94EDBD94F18B9729');
        $this->addSql('ALTER TABLE healing_component ADD CONSTRAINT FK_94EDBD94F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE equipment_component DROP FOREIGN KEY FK_9BC3E72EF18B9729');
        $this->addSql('ALTER TABLE equipment_component ADD CONSTRAINT FK_9BC3E72EF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE item_bag_component DROP FOREIGN KEY FK_A0874AACF18B9729');
        $this->addSql('ALTER TABLE item_bag_component ADD CONSTRAINT FK_A0874AACF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE render_component DROP FOREIGN KEY FK_BD9FD430F18B9729');
        $this->addSql('ALTER TABLE render_component ADD CONSTRAINT FK_BD9FD430F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE item_component DROP FOREIGN KEY FK_4524B7A7F18B9729');
        $this->addSql('ALTER TABLE item_component ADD CONSTRAINT FK_4524B7A7F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE resource_attached_component DROP FOREIGN KEY FK_F9BB3C9BF18B9729');
        $this->addSql('ALTER TABLE resource_attached_component ADD CONSTRAINT FK_D10BB072F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE resource_attached_component RENAME INDEX uniq_f9bb3c9bf18b9729 TO UNIQ_D10BB072F18B9729');
        $this->addSql('ALTER TABLE item_in_bag_slot_component DROP FOREIGN KEY FK_E682F318F18B9729');
        $this->addSql('ALTER TABLE item_in_bag_slot_component ADD CONSTRAINT FK_E682F318F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE map_in_component DROP FOREIGN KEY FK_B351C81AF18B9729');
        $this->addSql('ALTER TABLE map_in_component ADD CONSTRAINT FK_B351C81AF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE combat_component DROP FOREIGN KEY FK_A5E80D13F18B9729');
        $this->addSql('ALTER TABLE combat_component ADD CONSTRAINT FK_A5E80D13F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE resource_component DROP FOREIGN KEY FK_E137F2C8F18B9729');
        $this->addSql('ALTER TABLE resource_component ADD CONSTRAINT FK_E137F2C8F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE map_component DROP FOREIGN KEY FK_EC96E60AF18B9729');
        $this->addSql('ALTER TABLE map_component ADD CONSTRAINT FK_EC96E60AF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE character_component DROP FOREIGN KEY FK_CEAE036CF18B9729');
        $this->addSql('ALTER TABLE character_component ADD CONSTRAINT FK_CEAE036CF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
