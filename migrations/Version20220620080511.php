<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220620080511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE legume ADD maraicher_id INT NOT NULL');
        $this->addSql('ALTER TABLE legume ADD CONSTRAINT FK_866673837D6FCEE4 FOREIGN KEY (maraicher_id) REFERENCES maraicher (id)');
        $this->addSql('CREATE INDEX IDX_866673837D6FCEE4 ON legume (maraicher_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE legume DROP FOREIGN KEY FK_866673837D6FCEE4');
        $this->addSql('DROP INDEX IDX_866673837D6FCEE4 ON legume');
        $this->addSql('ALTER TABLE legume DROP maraicher_id');
    }
}
