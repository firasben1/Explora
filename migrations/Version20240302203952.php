<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302203952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE evenement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, nbr_participant INT NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, evenement_id INT DEFAULT NULL, url_image VARCHAR(255) NOT NULL, INDEX IDX_E01FBE6AFD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participation (id INT AUTO_INCREMENT NOT NULL, reservation_id INT DEFAULT NULL, email VARCHAR(255) NOT NULL, numero INT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, INDEX IDX_AB55E24FB83297E7 (reservation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id INT AUTO_INCREMENT NOT NULL, evenement_id INT DEFAULT NULL, user_id INT DEFAULT NULL, etat VARCHAR(255) NOT NULL, nb_participant INT NOT NULL, INDEX IDX_42C84955FD02F13 (evenement_id), INDEX IDX_42C84955A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE article CHANGE description description VARCHAR(65535) NOT NULL');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB181A8BA');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBFE6148B');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBFE6148B FOREIGN KEY (reponse_location_id) REFERENCES reponse_location (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6AFD02F13');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FB83297E7');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955FD02F13');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE images');
        $this->addSql('DROP TABLE participation');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('ALTER TABLE article CHANGE description description MEDIUMTEXT NOT NULL');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB181A8BA');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CBFE6148B');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CBFE6148B FOREIGN KEY (reponse_location_id) REFERENCES reponse_location (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
