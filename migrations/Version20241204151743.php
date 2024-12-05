<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241204151743 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_player_character DROP FOREIGN KEY FK_B0D0B123E26FCE68');
        $this->addSql('ALTER TABLE data_mastery_skill DROP FOREIGN KEY FK_78CAB74959C60354');
        $this->addSql('ALTER TABLE data_mastery_recipe DROP FOREIGN KEY FK_1BCF5BCF59D8A214');
        $this->addSql('ALTER TABLE data_mastery_recipe DROP FOREIGN KEY FK_1BCF5BCF59C60354');
        $this->addSql('ALTER TABLE data_mastery_item_type DROP FOREIGN KEY FK_B310FE2ECE11AAC7');
        $this->addSql('ALTER TABLE data_mastery_item_type DROP FOREIGN KEY FK_B310FE2E59C60354');
        $this->addSql('ALTER TABLE data_mastery_item DROP FOREIGN KEY FK_3178B72E59C60354');
        $this->addSql('ALTER TABLE data_mastery_item DROP FOREIGN KEY FK_3178B72E126F525E');
        $this->addSql('DROP TABLE data_mastery_skill');
        $this->addSql('DROP TABLE data_mastery_recipe');
        $this->addSql('DROP TABLE data_mastery_item_type');
        $this->addSql('DROP TABLE data_mastery_item');
        $this->addSql('DROP TABLE data_mastery');
        $this->addSql('DROP INDEX UNIQ_B0D0B123E26FCE68 ON data_player_character');
        $this->addSql('ALTER TABLE data_player_character DROP mastery_id, CHANGE masteries mastery_info JSON NOT NULL COMMENT \'(DC2Type:json_document)\'');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE data_mastery_skill (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', mastery_collection_id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', experience DOUBLE PRECISION NOT NULL, skill VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, INDEX IDX_78CAB74959C60354 (mastery_collection_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE data_mastery_recipe (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', mastery_collection_id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', recipe_id VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, experience DOUBLE PRECISION NOT NULL, INDEX IDX_1BCF5BCF59C60354 (mastery_collection_id), INDEX IDX_1BCF5BCF59D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE data_mastery_item_type (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', mastery_collection_id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', item_type_id VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, experience DOUBLE PRECISION NOT NULL, INDEX IDX_B310FE2E59C60354 (mastery_collection_id), INDEX IDX_B310FE2ECE11AAC7 (item_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE data_mastery_item (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', mastery_collection_id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', item_id VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, experience DOUBLE PRECISION NOT NULL, INDEX IDX_3178B72E59C60354 (mastery_collection_id), INDEX IDX_3178B72E126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE data_mastery (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE data_mastery_skill ADD CONSTRAINT FK_78CAB74959C60354 FOREIGN KEY (mastery_collection_id) REFERENCES data_mastery (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_mastery_recipe ADD CONSTRAINT FK_1BCF5BCF59D8A214 FOREIGN KEY (recipe_id) REFERENCES game_recipe (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_mastery_recipe ADD CONSTRAINT FK_1BCF5BCF59C60354 FOREIGN KEY (mastery_collection_id) REFERENCES data_mastery (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_mastery_item_type ADD CONSTRAINT FK_B310FE2ECE11AAC7 FOREIGN KEY (item_type_id) REFERENCES game_item_type (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_mastery_item_type ADD CONSTRAINT FK_B310FE2E59C60354 FOREIGN KEY (mastery_collection_id) REFERENCES data_mastery (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_mastery_item ADD CONSTRAINT FK_3178B72E59C60354 FOREIGN KEY (mastery_collection_id) REFERENCES data_mastery (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_mastery_item ADD CONSTRAINT FK_3178B72E126F525E FOREIGN KEY (item_id) REFERENCES game_item (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_player_character ADD mastery_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', CHANGE mastery_info masteries JSON NOT NULL COMMENT \'(DC2Type:json_document)\'');
        $this->addSql('ALTER TABLE data_player_character ADD CONSTRAINT FK_B0D0B123E26FCE68 FOREIGN KEY (mastery_id) REFERENCES data_mastery (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_B0D0B123E26FCE68 ON data_player_character (mastery_id)');
    }
}
