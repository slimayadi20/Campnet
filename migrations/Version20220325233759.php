<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220325233759 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE ReservationI (id INT AUTO_INCREMENT NOT NULL, key_reserv_id INT DEFAULT NULL, nbr_p INT NOT NULL, Date DATE NOT NULL, date_fin DATE NOT NULL, INDEX IDX_DE70A073C6EC8CA (key_reserv_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE ReservationI ADD CONSTRAINT FK_DE70A073C6EC8CA FOREIGN KEY (key_reserv_id) REFERENCES CentreCamp (id)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_C454C68217E1F4EB FOREIGN KEY (Evenement_id) REFERENCES Evenement (id)');
        $this->addSql('CREATE INDEX IDX_C454C68217E1F4EB ON reservation (Evenement_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE ReservationI');
        $this->addSql('ALTER TABLE Reservation DROP FOREIGN KEY FK_C454C68217E1F4EB');
        $this->addSql('DROP INDEX IDX_C454C68217E1F4EB ON Reservation');
    }
}
