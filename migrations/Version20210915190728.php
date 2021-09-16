<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210915190728 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5EC3E05390A FOREIGN KEY (aut_id) REFERENCES utilisateur (id)');
        $this->addSql('CREATE INDEX IDX_EDBFD5EC3E05390A ON historique (aut_id)');
        $this->addSql('ALTER TABLE repertoire ADD CONSTRAINT FK_3C3678766D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('CREATE INDEX IDX_3C3678766D861B89 ON repertoire (equipe_id)');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B783C33F7837');
        $this->addSql('DROP INDEX IDX_389B783C33F7837 ON tag');
        $this->addSql('ALTER TABLE tag DROP document_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE historique DROP FOREIGN KEY FK_EDBFD5EC3E05390A');
        $this->addSql('DROP INDEX IDX_EDBFD5EC3E05390A ON historique');
        $this->addSql('ALTER TABLE repertoire DROP FOREIGN KEY FK_3C3678766D861B89');
        $this->addSql('DROP INDEX IDX_3C3678766D861B89 ON repertoire');
        $this->addSql('ALTER TABLE tag ADD document_id INT NOT NULL');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('CREATE INDEX IDX_389B783C33F7837 ON tag (document_id)');
    }
}
