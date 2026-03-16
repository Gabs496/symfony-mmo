<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260307141430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE in_map_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', map_id VARCHAR(255) NOT NULL, place VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_35B14A79F18B9729 (game_object_id), INDEX IDX_35B14A7953C55F64 (map_id), INDEX IDX_35B14A79741D53CD (place), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_in_bag_slot_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', max_quantity INT NOT NULL, bag_id VARCHAR(255) NOT NULL, quantity INT NOT NULL, UNIQUE INDEX UNIQ_E682F318F18B9729 (game_object_id), UNIQUE INDEX UNIQ_E682F3186F5D8297F18B9729 (bag_id, game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE in_map_component ADD CONSTRAINT FK_35B14A79F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE item_in_bag_slot_component ADD CONSTRAINT FK_E682F318F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE item_bag_slot DROP FOREIGN KEY FK_94D86FB66F5D8297');
        $this->addSql('ALTER TABLE item_bag_slot DROP FOREIGN KEY FK_94D86FB6126F525E');
        $this->addSql('DROP TABLE item_bag_slot');
        $this->addSql('ALTER TABLE item_component DROP max_stack_size');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_bag_slot (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', bag_id CHAR(36) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', item_id CHAR(36) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', max_quantity INT NOT NULL, quantity INT NOT NULL, UNIQUE INDEX UNIQ_94D86FB6126F525E (item_id), INDEX IDX_94D86FB66F5D8297 (bag_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE item_bag_slot ADD CONSTRAINT FK_94D86FB66F5D8297 FOREIGN KEY (bag_id) REFERENCES item_bag_component (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE item_bag_slot ADD CONSTRAINT FK_94D86FB6126F525E FOREIGN KEY (item_id) REFERENCES item_bag_component (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE in_map_component DROP FOREIGN KEY FK_35B14A79F18B9729');
        $this->addSql('ALTER TABLE item_in_bag_slot_component DROP FOREIGN KEY FK_E682F318F18B9729');
        $this->addSql('DROP TABLE in_map_component');
        $this->addSql('DROP TABLE item_in_bag_slot_component');
        $this->addSql('ALTER TABLE item_component ADD max_stack_size INT NOT NULL');
    }
}
