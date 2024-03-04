<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302123333 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE description description VARCHAR(65535) NOT NULL');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB181A8BA');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id)');
        $this->addSql('ALTER TABLE reponse_location DROP FOREIGN KEY FK_11206BC1E5FEC79');
        $this->addSql('ALTER TABLE reponse_location ADD CONSTRAINT FK_11206BC1E5FEC79 FOREIGN KEY (id_location_id) REFERENCES location (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE description description MEDIUMTEXT NOT NULL');
        $this->addSql('ALTER TABLE location DROP FOREIGN KEY FK_5E9E89CB181A8BA');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB181A8BA FOREIGN KEY (voiture_id) REFERENCES voiture (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reponse_location DROP FOREIGN KEY FK_11206BC1E5FEC79');
        $this->addSql('ALTER TABLE reponse_location ADD CONSTRAINT FK_11206BC1E5FEC79 FOREIGN KEY (id_location_id) REFERENCES location (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
