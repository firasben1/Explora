<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303002014 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE images DROP FOREIGN KEY FK_E01FBE6AFD02F13');
        $this->addSql('DROP TABLE images');
        $this->addSql('ALTER TABLE article CHANGE description description VARCHAR(65535) NOT NULL');
        $this->addSql('ALTER TABLE evenement ADD image VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FB83297E7');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE images (id INT AUTO_INCREMENT NOT NULL, evenement_id INT DEFAULT NULL, url_image VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, INDEX IDX_E01FBE6AFD02F13 (evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE images ADD CONSTRAINT FK_E01FBE6AFD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE article CHANGE description description MEDIUMTEXT NOT NULL');
        $this->addSql('ALTER TABLE evenement DROP image');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FB83297E7');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
