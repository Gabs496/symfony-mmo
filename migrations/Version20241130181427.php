<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241130181427 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE security_user (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE data_player_character ADD position_id VARCHAR(50) NOT NULL, ADD user_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B123A76ED395 FOREIGN KEY (user_id) REFERENCES security_user (id)');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B123DD842E46 FOREIGN KEY (position_id) REFERENCES game_map (id)');
        $this->addSql('CREATE INDEX IDX_B0D0B123A76ED395 ON data_player_character (user_id)');
        $this->addSql('CREATE INDEX IDX_B0D0B123DD842E46 ON data_player_character (position_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B123A76ED395');
        $this->addSql('DROP TABLE security_user');
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B123DD842E46');
        $this->addSql('DROP INDEX IDX_B0D0B123A76ED395 ON data_player_character');
        $this->addSql('DROP INDEX IDX_B0D0B123DD842E46 ON data_player_character');
        $this->addSql('ALTER TABLE data_player_character DROP user_id');
    }
}
