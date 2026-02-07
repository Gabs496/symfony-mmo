<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260117014310 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE map_map_object DROP FOREIGN KEY FK_2F5B73DF18B9729');
        $this->addSql('DROP TABLE map_map_object');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE map_map_object (id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', game_object_id CHAR(36) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci` COMMENT \'(DC2Type:guid)\', map_id VARCHAR(50) CHARACTER SET utf8mb3 NOT NULL COLLATE `utf8mb3_unicode_ci`, spawned_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', UNIQUE INDEX UNIQ_F95635DEF18B9729 (game_object_id), INDEX IDX_F95635DE53C55F64 (map_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb3 COLLATE `utf8mb3_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE map_map_object ADD CONSTRAINT FK_2F5B73DF18B9729 FOREIGN KEY (game_object_id) REFERENCES core_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
