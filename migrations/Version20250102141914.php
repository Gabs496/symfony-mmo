<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250102141914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_item_instance_bag DROP FOREIGN KEY FK_C56B4399E6F5DF');
        $this->addSql('DROP INDEX IDX_C56B4399E6F5DF ON data_item_instance_bag');
        $this->addSql('ALTER TABLE data_item_instance_bag DROP player_id');
        $this->addSql('ALTER TABLE data_player_character ADD backpack_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B12331009DBE FOREIGN KEY (backpack_id) REFERENCES data_item_instance_bag (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B0D0B12331009DBE ON data_player_character (backpack_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_item_instance_bag ADD player_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE data_item_instance_bag ADD CONSTRAINT FK_C56B4399E6F5DF FOREIGN KEY (player_id) REFERENCES data_player_character (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_C56B4399E6F5DF ON data_item_instance_bag (player_id)');
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B12331009DBE');
        $this->addSql('DROP INDEX UNIQ_B0D0B12331009DBE ON data_player_character');
        $this->addSql('ALTER TABLE data_player_character DROP backpack_id');
    }
}
