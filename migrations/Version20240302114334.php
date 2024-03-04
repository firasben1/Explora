<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302114334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE location (id INT AUTO_INCREMENT NOT NULL, voiture_id INT DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, mail VARCHAR(255) DEFAULT NULL, mobile INT DEFAULT NULL, adresse VARCHAR(255) DEFAULT NULL, date_prise DATETIME DEFAULT NULL, date_depot DATETIME DEFAULT NULL, num_cin INT DEFAULT NULL, prix INT DEFAULT NULL, permis_conduite VARCHAR(255) DEFAULT NULL, chauffeur VARCHAR(255) DEFAULT NULL, INDEX IDX_5E9E89CB181A8BA (voiture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse_location (id INT AUTO_INCREMENT NOT NULL, id_location_id INT DEFAULT NULL, statut VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_11206BC1E5FEC79 (id_location_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE voiture (id INT AUTO_INCREMENT NOT NULL, categories VARCHAR(255) DEFAULT NULL, marque VARCHAR(255) DEFAULT NULL, couleur VARCHAR(255) DEFAULT NULL, prix VARCHAR(255) DEFAULT NULL, image VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
        $this->addSql('ALTER TABLE reponse_location ADD CONSTRAINT FK_11206BC1E5FEC79 FOREIGN KEY (id_location_id) REFERENCES location (id)');
        $this->addSql('ALTER TABLE article CHANGE description description VARCHAR(65535) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB181A8BA');
        $this->addSql('ALTER TABLE reponse_location DROP FOREIGN KEY FK_11206BC1E5FEC79');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE reponse_location');
        $this->addSql('DROP TABLE voiture');
        $this->addSql('ALTER TABLE article CHANGE description description MEDIUMTEXT NOT NULL');
    }
}
