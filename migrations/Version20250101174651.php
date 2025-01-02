<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250101174651 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE game_recipe DROP FOREIGN KEY FK_A7BD1CFFC99F6D6D');
        $this->addSql('ALTER TABLE game_recipe_ingredient DROP FOREIGN KEY FK_36EEB39B126F525E');
        $this->addSql('ALTER TABLE game_recipe_ingredient DROP FOREIGN KEY FK_36EEB39B59D8A214');
        $this->addSql('DROP TABLE game_recipe');
        $this->addSql('DROP TABLE game_recipe_ingredient');
        $this->addSql('ALTER TABLE data_activity DROP mastery_involveds');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE game_recipe (id VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, produced_item_id VARCHAR(50) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci`, skill VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, min_experience_required DOUBLE PRECISION NOT NULL, experience_reward DOUBLE PRECISION NOT NULL, UNIQUE INDEX UNIQ_A7BD1CFFC99F6D6D (produced_item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE game_recipe_ingredient (id VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, recipe_id VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, item_id VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, quantity INT NOT NULL, INDEX IDX_36EEB39B59D8A214 (recipe_id), INDEX IDX_36EEB39B126F525E (item_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE game_recipe ADD CONSTRAINT FK_A7BD1CFFC99F6D6D FOREIGN KEY (produced_item_id) REFERENCES game_item (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE game_recipe_ingredient ADD CONSTRAINT FK_36EEB39B126F525E FOREIGN KEY (item_id) REFERENCES game_item (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE game_recipe_ingredient ADD CONSTRAINT FK_36EEB39B59D8A214 FOREIGN KEY (recipe_id) REFERENCES game_recipe (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE data_activity ADD mastery_involveds JSON NOT NULL COMMENT \'(DC2Type:json_document)\'');
    }
}
