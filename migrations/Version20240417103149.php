<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240417103149 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE annexe (idannexe INT AUTO_INCREMENT NOT NULL, nomannexe VARCHAR(255) NOT NULL, localisation VARCHAR(255) NOT NULL, PRIMARY KEY(idannexe)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE article (idarticle INT AUTO_INCREMENT NOT NULL, idjournaliste_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_23A0E66A44D9016 (idjournaliste_id), PRIMARY KEY(idarticle)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE billet (idbillet INT AUTO_INCREMENT NOT NULL, idrencontre_id INT DEFAULT NULL, prixbillet INT NOT NULL, INDEX IDX_1F034AF62A537711 (idrencontre_id), PRIMARY KEY(idbillet)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE candidat (idc INT AUTO_INCREMENT NOT NULL, idelection_id INT DEFAULT NULL, nomc VARCHAR(255) NOT NULL, prenomc VARCHAR(255) NOT NULL, agec INT NOT NULL, imgcpath VARCHAR(255) NOT NULL, INDEX IDX_6AB5B47161C9AA50 (idelection_id), PRIMARY KEY(idc)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commande (idcommande INT AUTO_INCREMENT NOT NULL, idproduit_id INT DEFAULT NULL, iduser INT NOT NULL, somme INT NOT NULL, quantite INT NOT NULL, INDEX IDX_6EEAA67DC29D63C1 (idproduit_id), PRIMARY KEY(idcommande)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contrat (contrat_id INT AUTO_INCREMENT NOT NULL, employee_id INT DEFAULT NULL, date_debut DATETIME NOT NULL, date_fin DATETIME NOT NULL, salaire INT NOT NULL, INDEX IDX_603499938C03F15C (employee_id), PRIMARY KEY(contrat_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evenement (ide INT AUTO_INCREMENT NOT NULL, nome VARCHAR(255) NOT NULL, datee DATETIME NOT NULL, postee VARCHAR(255) NOT NULL, periodep VARCHAR(255) NOT NULL, imgepath VARCHAR(255) NOT NULL, PRIMARY KEY(ide)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE joueur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, age INT NOT NULL, position VARCHAR(255) NOT NULL, hauteur INT NOT NULL, poids INT NOT NULL, piedfort VARCHAR(255) NOT NULL, imagepath VARCHAR(255) NOT NULL, link VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE produit (idproduit INT AUTO_INCREMENT NOT NULL, category_id INT DEFAULT NULL, nomproduit VARCHAR(255) NOT NULL, prixproduit INT NOT NULL, tailleproduit VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, qauntiteproduit INT NOT NULL, image VARCHAR(255) NOT NULL, INDEX IDX_29A5EC2712469DE2 (category_id), PRIMARY KEY(idproduit)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (idreclamation INT AUTO_INCREMENT NOT NULL, iduser_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, etat INT NOT NULL, INDEX IDX_CE606404786A81FB (iduser_id), PRIMARY KEY(idreclamation)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE rencontre (idrencontre INT AUTO_INCREMENT NOT NULL, daterebcontre DATETIME NOT NULL, adversaire VARCHAR(255) NOT NULL, score VARCHAR(255) NOT NULL, PRIMARY KEY(idrencontre)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (reservationid INT AUTO_INCREMENT NOT NULL, idterrain_id INT DEFAULT NULL, datereservation VARCHAR(255) NOT NULL, note VARCHAR(255) NOT NULL, emplacement VARCHAR(255) NOT NULL, INDEX IDX_42C849554784967B (idterrain_id), PRIMARY KEY(reservationid)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE terrain (id INT AUTO_INCREMENT NOT NULL, nom_terrain VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, geo_x DOUBLE PRECISION NOT NULL, geo_y DOUBLE PRECISION NOT NULL, ouverture DATETIME NOT NULL, fermeture DATETIME NOT NULL, datedispo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (iduser INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, motdepasse VARCHAR(255) NOT NULL, role VARCHAR(255) NOT NULL, numtel INT NOT NULL, PRIMARY KEY(iduser)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE vote (idv INT AUTO_INCREMENT NOT NULL, idcandidatv INT NOT NULL, idelectionv INT NOT NULL, PRIMARY KEY(idv)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A44D9016 FOREIGN KEY (idjournaliste_id) REFERENCES user (iduser)');
        $this->addSql('ALTER TABLE billet ADD CONSTRAINT FK_1F034AF62A537711 FOREIGN KEY (idrencontre_id) REFERENCES rencontre (idrencontre)');
        $this->addSql('ALTER TABLE candidat ADD CONSTRAINT FK_6AB5B47161C9AA50 FOREIGN KEY (idelection_id) REFERENCES evenement (ide)');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DC29D63C1 FOREIGN KEY (idproduit_id) REFERENCES produit (idproduit)');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_603499938C03F15C FOREIGN KEY (employee_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404786A81FB FOREIGN KEY (iduser_id) REFERENCES user (iduser)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849554784967B FOREIGN KEY (idterrain_id) REFERENCES terrain (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A44D9016');
        $this->addSql('ALTER TABLE billet DROP FOREIGN KEY FK_1F034AF62A537711');
        $this->addSql('ALTER TABLE candidat DROP FOREIGN KEY FK_6AB5B47161C9AA50');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DC29D63C1');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_603499938C03F15C');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2712469DE2');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404786A81FB');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849554784967B');
        $this->addSql('DROP TABLE annexe');
        $this->addSql('DROP TABLE article');
        $this->addSql('DROP TABLE billet');
        $this->addSql('DROP TABLE candidat');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE commande');
        $this->addSql('DROP TABLE contrat');
        $this->addSql('DROP TABLE evenement');
        $this->addSql('DROP TABLE joueur');
        $this->addSql('DROP TABLE produit');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE rencontre');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE terrain');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE vote');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
