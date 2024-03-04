<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240303163816 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE description description VARCHAR(65535) NOT NULL');
        $this->addSql('ALTER TABLE evenement ADD date DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FB83297E7');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id)');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955FD02F13');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article CHANGE description description MEDIUMTEXT NOT NULL');
        $this->addSql('ALTER TABLE evenement DROP date');
        $this->addSql('ALTER TABLE participation DROP FOREIGN KEY FK_AB55E24FB83297E7');
        $this->addSql('ALTER TABLE participation ADD CONSTRAINT FK_AB55E24FB83297E7 FOREIGN KEY (reservation_id) REFERENCES reservation (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955FD02F13');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955A76ED395');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955FD02F13 FOREIGN KEY (evenement_id) REFERENCES evenement (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
