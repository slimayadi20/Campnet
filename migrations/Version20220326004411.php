<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220326004411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE CentreCamp (id INT AUTO_INCREMENT NOT NULL, nom_centre VARCHAR(255) NOT NULL, Description_centre VARCHAR(255) NOT NULL, img_centre VARCHAR(255) NOT NULL, lieux VARCHAR(255) NOT NULL, tlf_centre VARCHAR(8) NOT NULL, mail_centre VARCHAR(255) NOT NULL, mdps_centre VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Evenement (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Promo (id INT AUTO_INCREMENT NOT NULL, Nom_promo VARCHAR(255) NOT NULL, nv_prix VARCHAR(255) NOT NULL, Description_promo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Reservation (id INT AUTO_INCREMENT NOT NULL, nbr_pers INT NOT NULL, date DATETIME NOT NULL, date_r DATETIME NOT NULL, Evenement_id INT DEFAULT NULL, INDEX IDX_C454C68217E1F4EB (Evenement_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Reservation ADD CONSTRAINT FK_C454C68217E1F4EB FOREIGN KEY (Evenement_id) REFERENCES Evenement (id)');
        $this->addSql('DROP TABLE resevation');
        $this->addSql('ALTER TABLE reservationi ADD CONSTRAINT FK_DE70A073C6EC8CA FOREIGN KEY (key_reserv_id) REFERENCES CentreCamp (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ReservationI DROP FOREIGN KEY FK_DE70A073C6EC8CA');
        $this->addSql('ALTER TABLE Reservation DROP FOREIGN KEY FK_C454C68217E1F4EB');
        $this->addSql('CREATE TABLE resevation (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE CentreCamp');
        $this->addSql('DROP TABLE Evenement');
        $this->addSql('DROP TABLE Promo');
        $this->addSql('DROP TABLE Reservation');
    }
}
