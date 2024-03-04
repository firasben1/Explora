<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302133823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE description description VARCHAR(65535) NOT NULL');
        $this->addSql('ALTER TABLE reponse_location DROP FOREIGN KEY FK_11206BCFE6148B');
        $this->addSql('DROP INDEX UNIQ_11206BCFE6148B ON reponse_location');
        $this->addSql('ALTER TABLE reponse_location CHANGE reponse_location_id id_location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse_location ADD CONSTRAINT FK_11206BC1E5FEC79 FOREIGN KEY (id_location_id) REFERENCES location (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_11206BC1E5FEC79 ON reponse_location (id_location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE description description MEDIUMTEXT NOT NULL');
        $this->addSql('ALTER TABLE reponse_location DROP FOREIGN KEY FK_11206BC1E5FEC79');
        $this->addSql('DROP INDEX UNIQ_11206BC1E5FEC79 ON reponse_location');
        $this->addSql('ALTER TABLE reponse_location CHANGE id_location_id reponse_location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse_location ADD CONSTRAINT FK_11206BCFE6148B FOREIGN KEY (reponse_location_id) REFERENCES reponse_location (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_11206BCFE6148B ON reponse_location (reponse_location_id)');
    }
}
