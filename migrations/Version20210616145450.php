<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210616145450 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE repertoire ADD repertoire_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE repertoire ADD CONSTRAINT FK_3C3678761E61B789 FOREIGN KEY (repertoire_id) REFERENCES repertoire (id)');
        $this->addSql('CREATE INDEX IDX_3C3678761E61B789 ON repertoire (repertoire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE repertoire DROP FOREIGN KEY FK_3C3678761E61B789');
        $this->addSql('DROP INDEX IDX_3C3678761E61B789 ON repertoire');
        $this->addSql('ALTER TABLE repertoire DROP repertoire_id');
    }
}
