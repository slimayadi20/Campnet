<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220325170603 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Adresse (id INT AUTO_INCREMENT NOT NULL, Nom VARCHAR(255) NOT NULL, Prenom VARCHAR(255) NOT NULL, Adress VARCHAR(255) NOT NULL, City VARCHAR(255) NOT NULL, Email VARCHAR(255) NOT NULL, Tel INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Commande (idcommande INT AUTO_INCREMENT NOT NULL, idlivreur_id INT DEFAULT NULL, adresse_id INT DEFAULT NULL, Produit VARCHAR(255) NOT NULL, Quantite INT NOT NULL, Total DOUBLE PRECISION NOT NULL, date DATETIME NOT NULL, INDEX IDX_979CC42B35CD7A3B (idlivreur_id), INDEX IDX_979CC42B4DE7DC5C (adresse_id), PRIMARY KEY(idcommande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Livreur (id INT AUTO_INCREMENT NOT NULL, Nom VARCHAR(255) NOT NULL, Prenom VARCHAR(255) NOT NULL, Tel INT NOT NULL, Email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Panier (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, quantite INT NOT NULL, prix DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Produit (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, description VARCHAR(255) NOT NULL, disponibilte VARCHAR(255) NOT NULL, photo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE Resevation (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE User (id INT AUTO_INCREMENT NOT NULL, Nom VARCHAR(255) NOT NULL, Prenom VARCHAR(255) NOT NULL, date_naissance DATE NOT NULL, GSM INT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, activation_token VARCHAR(50) DEFAULT NULL, reset_token VARCHAR(50) DEFAULT NULL, UNIQUE INDEX UNIQ_2DA17977E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Commande ADD CONSTRAINT FK_979CC42B35CD7A3B FOREIGN KEY (idlivreur_id) REFERENCES Livreur (id)');
        $this->addSql('ALTER TABLE Commande ADD CONSTRAINT FK_979CC42B4DE7DC5C FOREIGN KEY (adresse_id) REFERENCES Adresse (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Commande DROP FOREIGN KEY FK_979CC42B4DE7DC5C');
        $this->addSql('ALTER TABLE Commande DROP FOREIGN KEY FK_979CC42B35CD7A3B');
        $this->addSql('DROP TABLE Adresse');
        $this->addSql('DROP TABLE Commande');
        $this->addSql('DROP TABLE Livreur');
        $this->addSql('DROP TABLE Panier');
        $this->addSql('DROP TABLE Produit');
        $this->addSql('DROP TABLE Resevation');
        $this->addSql('DROP TABLE User');
    }
}
