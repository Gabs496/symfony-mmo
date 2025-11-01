<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250805151139 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE game_map_object DROP FOREIGN KEY FK_2F5B73DF18B9729
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_map_object ADD CONSTRAINT FK_2F5B73DF18B9729 FOREIGN KEY (game_object_id) REFERENCES game_game_object (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_2F5B73D53C55F64 ON game_map_object (map_id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE game_map_object DROP FOREIGN KEY FK_2F5B73DF18B9729
        SQL);
        $this->addSql(<<<'SQL'
            DROP INDEX IDX_2F5B73D53C55F64 ON game_map_object
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE game_map_object ADD CONSTRAINT FK_2F5B73DF18B9729 FOREIGN KEY (game_object_id) REFERENCES game_game_object (id) ON UPDATE NO ACTION ON DELETE NO ACTION
        SQL);
    }
}
