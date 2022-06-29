<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220629113908 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE details_commande ADD legume_id INT NOT NULL');
        $this->addSql('ALTER TABLE details_commande ADD CONSTRAINT FK_4BCD5F625F18E37 FOREIGN KEY (legume_id) REFERENCES legume (id)');
        $this->addSql('CREATE INDEX IDX_4BCD5F625F18E37 ON details_commande (legume_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE details_commande DROP FOREIGN KEY FK_4BCD5F625F18E37');
        $this->addSql('DROP INDEX IDX_4BCD5F625F18E37 ON details_commande');
        $this->addSql('ALTER TABLE details_commande DROP legume_id');
    }
}
