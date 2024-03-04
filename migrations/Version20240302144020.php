<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240302144020 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE description description VARCHAR(65535) NOT NULL');
        $this->addSql('ALTER TABLE reponse_location ADD location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reponse_location ADD CONSTRAINT FK_11206BC64D218E FOREIGN KEY (location_id) REFERENCES location (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_11206BC64D218E ON reponse_location (location_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE description description MEDIUMTEXT NOT NULL');
        $this->addSql('ALTER TABLE reponse_location DROP FOREIGN KEY FK_11206BC64D218E');
        $this->addSql('DROP INDEX UNIQ_11206BC64D218E ON reponse_location');
        $this->addSql('ALTER TABLE reponse_location DROP location_id');
    }
}
