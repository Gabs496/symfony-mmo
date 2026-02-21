<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260306005522 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE item_bag_slot (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', bag_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', item_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', max_quantity INT NOT NULL, quantity INT NOT NULL, INDEX IDX_94D86FB66F5D8297 (bag_id), UNIQUE INDEX UNIQ_94D86FB6126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_bag_slot ADD CONSTRAINT FK_94D86FB66F5D8297 FOREIGN KEY (bag_id) REFERENCES item_bag_component (id)');
        $this->addSql('ALTER TABLE item_bag_slot ADD CONSTRAINT FK_94D86FB6126F525E FOREIGN KEY (item_id) REFERENCES item_bag_component (id)');
        $this->addSql('ALTER TABLE placed_component DROP FOREIGN KEY FK_21E95A1AF18B9729');
        $this->addSql('DROP TABLE placed_component');
        $this->addSql('ALTER TABLE item_bag_component CHANGE size max_size DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE item_component DROP quantity');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE placed_component (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', place_type VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, place_id VARCHAR(100) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, INDEX IDX_21E95A1A466B27C5DA6A219 (place_type, place_id), UNIQUE INDEX UNIQ_21E95A1AF18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE placed_component ADD CONSTRAINT FK_21E95A1AF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE item_bag_slot DROP FOREIGN KEY FK_94D86FB66F5D8297');
        $this->addSql('ALTER TABLE item_bag_slot DROP FOREIGN KEY FK_94D86FB6126F525E');
        $this->addSql('DROP TABLE item_bag_slot');
        $this->addSql('ALTER TABLE item_bag_component CHANGE max_size size DOUBLE PRECISION NOT NULL');
        $this->addSql('ALTER TABLE item_component ADD quantity INT NOT NULL');
    }
}
