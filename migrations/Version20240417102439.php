<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240417102439 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE categorie');
        $this->addSql('DROP INDEX const ON article');
        $this->addSql('ALTER TABLE article ADD idjournaliste_id INT DEFAULT NULL, DROP IdJournaliste, CHANGE Description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A44D9016 FOREIGN KEY (idjournaliste_id) REFERENCES user (iduser)');
        $this->addSql('CREATE INDEX IDX_23A0E66A44D9016 ON article (idjournaliste_id)');
        $this->addSql('DROP INDEX Billet ON billet');
        $this->addSql('ALTER TABLE billet ADD idrencontre_id INT DEFAULT NULL, DROP IdRencontre');
        $this->addSql('ALTER TABLE billet ADD CONSTRAINT FK_1F034AF62A537711 FOREIGN KEY (idrencontre_id) REFERENCES rencontre (idrencontre)');
        $this->addSql('CREATE INDEX IDX_1F034AF62A537711 ON billet (idrencontre_id)');
        $this->addSql('ALTER TABLE contrat CHANGE contrat_id contrat_id INT AUTO_INCREMENT NOT NULL, CHANGE date_debut date_debut DATETIME NOT NULL, CHANGE date_fin date_fin DATETIME NOT NULL');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_603499938C03F15C FOREIGN KEY (employee_id) REFERENCES joueur (id)');
        $this->addSql('DROP INDEX employee_id ON contrat');
        $this->addSql('CREATE INDEX IDX_603499938C03F15C ON contrat (employee_id)');
        $this->addSql('ALTER TABLE evenement CHANGE dateE datee DATETIME NOT NULL');
        $this->addSql('ALTER TABLE joueur ADD link VARCHAR(255) NOT NULL, CHANGE Piedfort piedfort VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY TypeFK');
        $this->addSql('DROP INDEX IDX_29A5EC272CECF817 ON produit');
        $this->addSql('ALTER TABLE produit ADD category_id INT DEFAULT NULL, CHANGE TailleProduit tailleproduit VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC2712469DE2 ON produit (category_id)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_ibfk_1');
        $this->addSql('DROP INDEX IdUser ON reclamation');
        $this->addSql('ALTER TABLE reclamation ADD iduser_id INT DEFAULT NULL, DROP IdUser, CHANGE Description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404786A81FB FOREIGN KEY (iduser_id) REFERENCES user (iduser)');
        $this->addSql('CREATE INDEX IDX_CE606404786A81FB ON reclamation (iduser_id)');
        $this->addSql('ALTER TABLE rencontre CHANGE DateRebcontre daterebcontre DATETIME NOT NULL');
        $this->addSql('ALTER TABLE reservation MODIFY IdReservation INT NOT NULL');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_2');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_1');
        $this->addSql('DROP INDEX IdUser ON reservation');
        $this->addSql('DROP INDEX An ON reservation');
        $this->addSql('DROP INDEX `primary` ON reservation');
        $this->addSql('ALTER TABLE reservation ADD idterrain_id INT DEFAULT NULL, ADD datereservation VARCHAR(255) NOT NULL, ADD note VARCHAR(255) NOT NULL, ADD emplacement VARCHAR(255) NOT NULL, DROP Date, DROP IdAnnexe, DROP IdUser, CHANGE IdReservation reservationid INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849554784967B FOREIGN KEY (idterrain_id) REFERENCES terrain (id)');
        $this->addSql('CREATE INDEX IDX_42C849554784967B ON reservation (idterrain_id)');
        $this->addSql('ALTER TABLE reservation ADD PRIMARY KEY (reservationid)');
        $this->addSql('ALTER TABLE terrain CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE adresse adresse VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE geo_x geo_x DOUBLE PRECISION NOT NULL, CHANGE geo_y geo_y DOUBLE PRECISION NOT NULL, CHANGE ouverture ouverture DATETIME NOT NULL, CHANGE fermeture fermeture DATETIME NOT NULL, CHANGE datedispo datedispo VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE user ADD email VARCHAR(255) NOT NULL, ADD numtel INT NOT NULL, DROP Nom, DROP Prenom, DROP Mail, CHANGE IdUser iduser INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE vote ADD idcandidatv INT NOT NULL, ADD idelectionv INT NOT NULL, DROP idC, DROP idMp');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2712469DE2');
        $this->addSql('CREATE TABLE categorie (id INT AUTO_INCREMENT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE category');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A44D9016');
        $this->addSql('DROP INDEX IDX_23A0E66A44D9016 ON article');
        $this->addSql('ALTER TABLE article ADD IdJournaliste INT NOT NULL, DROP idjournaliste_id, CHANGE description Description VARCHAR(1100) NOT NULL');
        $this->addSql('CREATE INDEX const ON article (IdJournaliste)');
        $this->addSql('ALTER TABLE billet DROP FOREIGN KEY FK_1F034AF62A537711');
        $this->addSql('DROP INDEX IDX_1F034AF62A537711 ON billet');
        $this->addSql('ALTER TABLE billet ADD IdRencontre INT NOT NULL, DROP idrencontre_id');
        $this->addSql('CREATE INDEX Billet ON billet (IdRencontre)');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_603499938C03F15C');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_603499938C03F15C');
        $this->addSql('ALTER TABLE contrat CHANGE contrat_id contrat_id INT NOT NULL, CHANGE date_debut date_debut DATE DEFAULT NULL, CHANGE date_fin date_fin DATE DEFAULT NULL');
        $this->addSql('DROP INDEX idx_603499938c03f15c ON contrat');
        $this->addSql('CREATE INDEX employee_id ON contrat (employee_id)');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_603499938C03F15C FOREIGN KEY (employee_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE evenement CHANGE datee dateE DATE NOT NULL');
        $this->addSql('ALTER TABLE joueur DROP link, CHANGE piedfort Piedfort INT NOT NULL');
        $this->addSql('DROP INDEX IDX_29A5EC2712469DE2 ON produit');
        $this->addSql('ALTER TABLE produit DROP category_id, CHANGE tailleproduit TailleProduit VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT TypeFK FOREIGN KEY (Type) REFERENCES cathegorie (Type) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IDX_29A5EC272CECF817 ON produit (Type)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404786A81FB');
        $this->addSql('DROP INDEX IDX_CE606404786A81FB ON reclamation');
        $this->addSql('ALTER TABLE reclamation ADD IdUser INT NOT NULL, DROP iduser_id, CHANGE description Description VARCHAR(1000) NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_ibfk_1 FOREIGN KEY (IdUser) REFERENCES user (IdUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IdUser ON reclamation (IdUser)');
        $this->addSql('ALTER TABLE rencontre CHANGE daterebcontre DateRebcontre DATE NOT NULL');
        $this->addSql('ALTER TABLE reservation MODIFY reservationid INT NOT NULL');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849554784967B');
        $this->addSql('DROP INDEX IDX_42C849554784967B ON reservation');
        $this->addSql('DROP INDEX `PRIMARY` ON reservation');
        $this->addSql('ALTER TABLE reservation ADD Date DATE NOT NULL, ADD IdAnnexe INT NOT NULL, ADD IdUser INT NOT NULL, DROP idterrain_id, DROP datereservation, DROP note, DROP emplacement, CHANGE reservationid IdReservation INT AUTO_INCREMENT NOT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT reservation_ibfk_2 FOREIGN KEY (IdUser) REFERENCES user (IdUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT reservation_ibfk_1 FOREIGN KEY (IdAnnexe) REFERENCES annexe (IdAnnexe) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX IdUser ON reservation (IdUser)');
        $this->addSql('CREATE INDEX An ON reservation (IdAnnexe)');
        $this->addSql('ALTER TABLE reservation ADD PRIMARY KEY (IdReservation)');
        $this->addSql('ALTER TABLE terrain CHANGE id id INT NOT NULL, CHANGE adresse adresse VARCHAR(255) DEFAULT NULL, CHANGE description description TEXT DEFAULT NULL, CHANGE geo_x geo_x DOUBLE PRECISION DEFAULT NULL, CHANGE geo_y geo_y DOUBLE PRECISION DEFAULT NULL, CHANGE ouverture ouverture TIME DEFAULT NULL, CHANGE fermeture fermeture TIME DEFAULT NULL, CHANGE datedispo datedispo VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE user ADD Prenom VARCHAR(255) NOT NULL, ADD Mail VARCHAR(255) NOT NULL, DROP numtel, CHANGE iduser IdUser INT NOT NULL, CHANGE email Nom VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE vote ADD idC INT NOT NULL, ADD idMp INT NOT NULL, DROP idcandidatv, DROP idelectionv');
    }
}
