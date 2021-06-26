<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210618170336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file DROP FOREIGN KEY FK_8C9F3610C33F7837');
        $this->addSql('DROP INDEX UNIQ_8C9F3610C33F7837 ON file');
        $this->addSql('ALTER TABLE file DROP document_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE file ADD document_id INT NOT NULL');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8C9F3610C33F7837 ON file (document_id)');
    }
}
