<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210621103644 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE repertoire DROP FOREIGN KEY FK_3C3678766D861B89');
        $this->addSql('DROP INDEX IDX_3C3678766D861B89 ON repertoire');
        $this->addSql('ALTER TABLE repertoire DROP equipe_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE repertoire ADD equipe_id INT NOT NULL');
        $this->addSql('ALTER TABLE repertoire ADD CONSTRAINT FK_3C3678766D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('CREATE INDEX IDX_3C3678766D861B89 ON repertoire (equipe_id)');
    }
}
