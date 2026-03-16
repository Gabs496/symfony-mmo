<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260316003430 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE resource_attached_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', max_avaliability INT NOT NULL, availability INT NOT NULL, UNIQUE INDEX UNIQ_D10BB072F18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE map_in_component (id CHAR(36) NOT NULL COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\', map_id VARCHAR(255) NOT NULL, place VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_B351C81AF18B9729 (game_object_id), INDEX IDX_B351C81A53C55F64 (map_id), INDEX IDX_B351C81A741D53CD (place), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE resource_attached_component ADD CONSTRAINT FK_D10BB072F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE map_in_component ADD CONSTRAINT FK_B351C81AF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id)');
        $this->addSql('ALTER TABLE in_map_component DROP FOREIGN KEY FK_35B14A79F18B9729');
        $this->addSql('ALTER TABLE attached_resource_component DROP FOREIGN KEY FK_CEB14491F18B9729');
        $this->addSql('DROP TABLE in_map_component');
        $this->addSql('DROP TABLE attached_resource_component');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE in_map_component (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', map_id VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, place VARCHAR(255) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, UNIQUE INDEX UNIQ_35B14A79F18B9729 (game_object_id), INDEX IDX_35B14A7953C55F64 (map_id), INDEX IDX_35B14A79741D53CD (place), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE attached_resource_component (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) CHARACTER SET utf8mb3 DEFAULT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', max_avaliability INT NOT NULL, availability INT NOT NULL, UNIQUE INDEX UNIQ_CEB14491F18B9729 (game_object_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE in_map_component ADD CONSTRAINT FK_35B14A79F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE attached_resource_component ADD CONSTRAINT FK_CEB14491F18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE resource_attached_component DROP FOREIGN KEY FK_D10BB072F18B9729');
        $this->addSql('ALTER TABLE map_in_component DROP FOREIGN KEY FK_B351C81AF18B9729');
        $this->addSql('DROP TABLE esource_attached_component');
        $this->addSql('DROP TABLE map_in_component');
    }
}
