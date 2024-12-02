<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20241127221952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE data_item_instance (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', item_id VARCHAR(50) DEFAULT NULL, bag_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', quantity INT NOT NULL, `condition` DOUBLE PRECISION NOT NULL, INDEX IDX_DF7F6D3F126F525E (item_id), INDEX IDX_DF7F6D3F6F5D8297 (bag_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE data_item_instance_bag (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', player_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', type VARCHAR(255) NOT NULL, INDEX IDX_C56B4399E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE data_map_resource_spot (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', map_id VARCHAR(50) NOT NULL, map_resource_id VARCHAR(50) NOT NULL, resource_quantity INT NOT NULL, INDEX IDX_2D6D8BC053C55F64 (map_id), INDEX IDX_2D6D8BC06E9FCF67 (map_resource_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE data_mastery_collection (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE data_mastery_item (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', mastery_collection_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', item_id VARCHAR(50) NOT NULL, experience DOUBLE PRECISION NOT NULL, INDEX IDX_3178B72E59C60354 (mastery_collection_id), INDEX IDX_3178B72E126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE data_mastery_item_type (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', mastery_collection_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', item_type_id VARCHAR(50) NOT NULL, experience DOUBLE PRECISION NOT NULL, INDEX IDX_B310FE2E59C60354 (mastery_collection_id), INDEX IDX_B310FE2ECE11AAC7 (item_type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE data_mastery_recipe (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', mastery_collection_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', recipe_id VARCHAR(50) NOT NULL, experience DOUBLE PRECISION NOT NULL, INDEX IDX_1BCF5BCF59C60354 (mastery_collection_id), INDEX IDX_1BCF5BCF59D8A214 (recipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE data_mastery_skill (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', mastery_collection_id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', experience DOUBLE PRECISION NOT NULL, skill VARCHAR(50) NOT NULL, INDEX IDX_78CAB74959C60354 (mastery_collection_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE data_player_character (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_item (id VARCHAR(50) NOT NULL, type_id VARCHAR(50) DEFAULT NULL, equippable TINYINT(1) NOT NULL, consumable TINYINT(1) NOT NULL, stackable TINYINT(1) NOT NULL, max_condition DOUBLE PRECISION NOT NULL, name VARCHAR(100) NOT NULL, description VARCHAR(255) NOT NULL, weight DOUBLE PRECISION NOT NULL, advised_experience DOUBLE PRECISION NOT NULL, INDEX IDX_F40E4932C54C8C93 (type_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_item_type (id VARCHAR(50) NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_map (id VARCHAR(50) NOT NULL, coordinate_x DOUBLE PRECISION NOT NULL, coordinate_y DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_map_resource (id VARCHAR(50) NOT NULL, map_resource_id VARCHAR(50) NOT NULL, map_id VARCHAR(50) NOT NULL, max_global_availability INT NOT NULL, max_spot_availability INT NOT NULL, spawn_frequency INT NOT NULL, INDEX IDX_30B3F65F6E9FCF67 (map_resource_id), INDEX IDX_30B3F65F53C55F64 (map_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_recipe (id VARCHAR(50) NOT NULL, produced_item_id VARCHAR(50) DEFAULT NULL, skill VARCHAR(50) NOT NULL, min_experience_required DOUBLE PRECISION NOT NULL, experience_reward DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_A7BD1CFFC99F6D6D (produced_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_recipe_ingredient (id VARCHAR(50) NOT NULL, recipe_id VARCHAR(50) NOT NULL, item_id VARCHAR(50) NOT NULL, quantity INT NOT NULL, INDEX IDX_36EEB39B59D8A214 (recipe_id), INDEX IDX_36EEB39B126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE game_resource (id VARCHAR(50) NOT NULL, difficulty DOUBLE PRECISION NOT NULL, skill_needed VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE data_item_instance ADD CONSTRAINT FK_DF7F6D3F126F525E FOREIGN KEY (item_id) REFERENCES game_item (id)');
        $this->addSql('ALTER TABLE data_item_instance ADD CONSTRAINT FK_DF7F6D3F6F5D8297 FOREIGN KEY (bag_id) REFERENCES data_item_instance_bag (id)');
        $this->addSql('ALTER TABLE data_item_instance_bag ADD CONSTRAINT FK_C56B4399E6F5DF FOREIGN KEY (player_id) REFERENCES data_player_character (id)');
        $this->addSql('ALTER TABLE data_map_resource_spot ADD CONSTRAINT FK_2D6D8BC053C55F64 FOREIGN KEY (map_id) REFERENCES game_map (id)');
        $this->addSql('ALTER TABLE data_map_resource_spot ADD CONSTRAINT FK_2D6D8BC06E9FCF67 FOREIGN KEY (map_resource_id) REFERENCES game_resource (id)');
        $this->addSql('ALTER TABLE data_mastery_item ADD CONSTRAINT FK_3178B72E59C60354 FOREIGN KEY (mastery_collection_id) REFERENCES data_mastery_collection (id)');
        $this->addSql('ALTER TABLE data_mastery_item ADD CONSTRAINT FK_3178B72E126F525E FOREIGN KEY (item_id) REFERENCES game_item (id)');
        $this->addSql('ALTER TABLE data_mastery_item_type ADD CONSTRAINT FK_B310FE2E59C60354 FOREIGN KEY (mastery_collection_id) REFERENCES data_mastery_collection (id)');
        $this->addSql('ALTER TABLE data_mastery_item_type ADD CONSTRAINT FK_B310FE2ECE11AAC7 FOREIGN KEY (item_type_id) REFERENCES game_item_type (id)');
        $this->addSql('ALTER TABLE data_mastery_recipe ADD CONSTRAINT FK_1BCF5BCF59C60354 FOREIGN KEY (mastery_collection_id) REFERENCES data_mastery_collection (id)');
        $this->addSql('ALTER TABLE data_mastery_recipe ADD CONSTRAINT FK_1BCF5BCF59D8A214 FOREIGN KEY (recipe_id) REFERENCES game_recipe (id)');
        $this->addSql('ALTER TABLE data_mastery_skill ADD CONSTRAINT FK_78CAB74959C60354 FOREIGN KEY (mastery_collection_id) REFERENCES data_mastery_collection (id)');
        $this->addSql('ALTER TABLE game_item ADD CONSTRAINT FK_F40E4932C54C8C93 FOREIGN KEY (type_id) REFERENCES game_item_type (id)');
        $this->addSql('ALTER TABLE game_map_resource ADD CONSTRAINT FK_30B3F65F6E9FCF67 FOREIGN KEY (map_resource_id) REFERENCES game_resource (id)');
        $this->addSql('ALTER TABLE game_map_resource ADD CONSTRAINT FK_30B3F65F53C55F64 FOREIGN KEY (map_id) REFERENCES game_map (id)');
        $this->addSql('ALTER TABLE game_recipe ADD CONSTRAINT FK_A7BD1CFFC99F6D6D FOREIGN KEY (produced_item_id) REFERENCES game_item (id)');
        $this->addSql('ALTER TABLE game_recipe_ingredient ADD CONSTRAINT FK_36EEB39B59D8A214 FOREIGN KEY (recipe_id) REFERENCES game_recipe (id)');
        $this->addSql('ALTER TABLE game_recipe_ingredient ADD CONSTRAINT FK_36EEB39B126F525E FOREIGN KEY (item_id) REFERENCES game_item (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE data_item_instance DROP FOREIGN KEY FK_DF7F6D3F126F525E');
        $this->addSql('ALTER TABLE data_item_instance DROP FOREIGN KEY FK_DF7F6D3F6F5D8297');
        $this->addSql('ALTER TABLE data_item_instance_bag DROP FOREIGN KEY FK_C56B4399E6F5DF');
        $this->addSql('ALTER TABLE data_map_resource_spot DROP FOREIGN KEY FK_2D6D8BC053C55F64');
        $this->addSql('ALTER TABLE data_map_resource_spot DROP FOREIGN KEY FK_2D6D8BC06E9FCF67');
        $this->addSql('ALTER TABLE data_mastery_item DROP FOREIGN KEY FK_3178B72E59C60354');
        $this->addSql('ALTER TABLE data_mastery_item DROP FOREIGN KEY FK_3178B72E126F525E');
        $this->addSql('ALTER TABLE data_mastery_item_type DROP FOREIGN KEY FK_B310FE2E59C60354');
        $this->addSql('ALTER TABLE data_mastery_item_type DROP FOREIGN KEY FK_B310FE2ECE11AAC7');
        $this->addSql('ALTER TABLE data_mastery_recipe DROP FOREIGN KEY FK_1BCF5BCF59C60354');
        $this->addSql('ALTER TABLE data_mastery_recipe DROP FOREIGN KEY FK_1BCF5BCF59D8A214');
        $this->addSql('ALTER TABLE data_mastery_skill DROP FOREIGN KEY FK_78CAB74959C60354');
        $this->addSql('ALTER TABLE game_item DROP FOREIGN KEY FK_F40E4932C54C8C93');
        $this->addSql('ALTER TABLE game_map_resource DROP FOREIGN KEY FK_30B3F65F6E9FCF67');
        $this->addSql('ALTER TABLE game_map_resource DROP FOREIGN KEY FK_30B3F65F53C55F64');
        $this->addSql('ALTER TABLE game_recipe DROP FOREIGN KEY FK_A7BD1CFFC99F6D6D');
        $this->addSql('ALTER TABLE game_recipe_ingredient DROP FOREIGN KEY FK_36EEB39B59D8A214');
        $this->addSql('ALTER TABLE game_recipe_ingredient DROP FOREIGN KEY FK_36EEB39B126F525E');
        $this->addSql('DROP TABLE data_item_instance');
        $this->addSql('DROP TABLE data_item_instance_bag');
        $this->addSql('DROP TABLE data_map_resource_spot');
        $this->addSql('DROP TABLE data_mastery_collection');
        $this->addSql('DROP TABLE data_mastery_item');
        $this->addSql('DROP TABLE data_mastery_item_type');
        $this->addSql('DROP TABLE data_mastery_recipe');
        $this->addSql('DROP TABLE data_mastery_skill');
        $this->addSql('DROP TABLE data_player_character');
        $this->addSql('DROP TABLE game_item');
        $this->addSql('DROP TABLE game_item_type');
        $this->addSql('DROP TABLE game_map');
        $this->addSql('DROP TABLE game_map_resource');
        $this->addSql('DROP TABLE game_recipe');
        $this->addSql('DROP TABLE game_recipe_ingredient');
        $this->addSql('DROP TABLE game_resource');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
