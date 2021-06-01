<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210531135258 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE commentaire (id INT AUTO_INCREMENT NOT NULL, document_id INT NOT NULL, comment LONGTEXT NOT NULL, INDEX IDX_67F068BCC33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, auteur_id INT NOT NULL, repertoire_id INT NOT NULL, nom VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, url_complet VARCHAR(255) NOT NULL, etat VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, taille VARCHAR(255) NOT NULL, date_creation DATETIME NOT NULL, version VARCHAR(255) NOT NULL, INDEX IDX_D8698A7660BB6FE6 (auteur_id), INDEX IDX_D8698A761E61B789 (repertoire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, projet_id INT NOT NULL, user_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_2449BA15C18272 (projet_id), INDEX IDX_2449BA15A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE historique (id INT AUTO_INCREMENT NOT NULL, document_id INT NOT NULL, date_modif DATETIME NOT NULL, INDEX IDX_EDBFD5ECC33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE projet (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE repertoire (id INT AUTO_INCREMENT NOT NULL, equipe_id INT NOT NULL, nom VARCHAR(255) NOT NULL, INDEX IDX_3C3678766D861B89 (equipe_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tag (id INT AUTO_INCREMENT NOT NULL, document_id INT NOT NULL, tag VARCHAR(255) NOT NULL, INDEX IDX_389B783C33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, login VARCHAR(255) NOT NULL, pwd VARCHAR(255) NOT NULL, gerant TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE commentaire ADD CONSTRAINT FK_67F068BCC33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7660BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A761E61B789 FOREIGN KEY (repertoire_id) REFERENCES repertoire (id)');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15C18272 FOREIGN KEY (projet_id) REFERENCES projet (id)');
        $this->addSql('ALTER TABLE equipe ADD CONSTRAINT FK_2449BA15A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE historique ADD CONSTRAINT FK_EDBFD5ECC33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE repertoire ADD CONSTRAINT FK_3C3678766D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id)');
        $this->addSql('ALTER TABLE tag ADD CONSTRAINT FK_389B783C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentaire DROP FOREIGN KEY FK_67F068BCC33F7837');
        $this->addSql('ALTER TABLE historique DROP FOREIGN KEY FK_EDBFD5ECC33F7837');
        $this->addSql('ALTER TABLE tag DROP FOREIGN KEY FK_389B783C33F7837');
        $this->addSql('ALTER TABLE repertoire DROP FOREIGN KEY FK_3C3678766D861B89');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15C18272');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A761E61B789');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7660BB6FE6');
        $this->addSql('ALTER TABLE equipe DROP FOREIGN KEY FK_2449BA15A76ED395');
        $this->addSql('DROP TABLE commentaire');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE historique');
        $this->addSql('DROP TABLE projet');
        $this->addSql('DROP TABLE repertoire');
        $this->addSql('DROP TABLE tag');
        $this->addSql('DROP TABLE user');
    }
}
