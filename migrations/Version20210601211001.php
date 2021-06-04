<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210601211001 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe ADD gerant_id INT NOT NULL');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15A500A924 FOREIGN KEY (gerant_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_2449BA15A500A924 ON equipe (gerant_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15A500A924');
        $this->addSql('DROP INDEX IDX_2449BA15A500A924 ON equipe');
        $this->addSql('ALTER TABLE equipe DROP gerant_id');
    }
}
