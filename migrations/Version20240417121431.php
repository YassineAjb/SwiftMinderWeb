<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240417121431 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY const');
        $this->addSql('DROP INDEX const ON article');
        $this->addSql('ALTER TABLE article ADD idjournaliste_id INT DEFAULT NULL, DROP IdJournaliste, CHANGE Description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT FK_23A0E66A44D9016 FOREIGN KEY (idjournaliste_id) REFERENCES user (iduser)');
        $this->addSql('CREATE INDEX IDX_23A0E66A44D9016 ON article (idjournaliste_id)');
        $this->addSql('ALTER TABLE billet DROP FOREIGN KEY billet_ibfk_1');
        $this->addSql('DROP INDEX Billet ON billet');
        $this->addSql('ALTER TABLE billet ADD idrencontre_id INT DEFAULT NULL, DROP IdRencontre');
        $this->addSql('ALTER TABLE billet ADD CONSTRAINT FK_1F034AF62A537711 FOREIGN KEY (idrencontre_id) REFERENCES rencontre (idrencontre)');
        $this->addSql('CREATE INDEX IDX_1F034AF62A537711 ON billet (idrencontre_id)');
        $this->addSql('ALTER TABLE candidat DROP FOREIGN KEY candidat_ibfk_1');
        $this->addSql('DROP INDEX idElection ON candidat');
        $this->addSql('ALTER TABLE candidat ADD idelection_id INT DEFAULT NULL, DROP idElection');
        $this->addSql('ALTER TABLE candidat ADD CONSTRAINT FK_6AB5B47161C9AA50 FOREIGN KEY (idelection_id) REFERENCES evenement (ide)');
        $this->addSql('CREATE INDEX IDX_6AB5B47161C9AA50 ON candidat (idelection_id)');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY commande_ibfk_1');
        $this->addSql('DROP INDEX commande_ibfk_1 ON commande');
        $this->addSql('ALTER TABLE commande ADD idproduit_id INT DEFAULT NULL, DROP idproduit');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT FK_6EEAA67DC29D63C1 FOREIGN KEY (idproduit_id) REFERENCES produit (idproduit)');
        $this->addSql('CREATE INDEX IDX_6EEAA67DC29D63C1 ON commande (idproduit_id)');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY contrat_ibfk_1');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY contrat_ibfk_1');
        $this->addSql('ALTER TABLE contrat CHANGE date_debut date_debut DATETIME NOT NULL, CHANGE date_fin date_fin DATETIME NOT NULL');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_603499938C03F15C FOREIGN KEY (employee_id) REFERENCES joueur (id)');
        $this->addSql('DROP INDEX employee_id ON contrat');
        $this->addSql('CREATE INDEX IDX_603499938C03F15C ON contrat (employee_id)');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT contrat_ibfk_1 FOREIGN KEY (employee_id) REFERENCES joueur (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('ALTER TABLE evenement CHANGE dateE datee DATETIME NOT NULL');
        $this->addSql('ALTER TABLE joueur CHANGE imagePath imagepath VARCHAR(255) NOT NULL, CHANGE Link link VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit ADD category_id INT DEFAULT NULL, CHANGE TailleProduit tailleproduit VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE produit ADD CONSTRAINT FK_29A5EC2712469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_29A5EC2712469DE2 ON produit (category_id)');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY reclamation_ibfk_1');
        $this->addSql('DROP INDEX reclamation_ibfk_1 ON reclamation');
        $this->addSql('ALTER TABLE reclamation ADD iduser_id INT DEFAULT NULL, DROP IdUser, CHANGE Description description VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404786A81FB FOREIGN KEY (iduser_id) REFERENCES user (iduser)');
        $this->addSql('CREATE INDEX IDX_CE606404786A81FB ON reclamation (iduser_id)');
        $this->addSql('ALTER TABLE rencontre CHANGE DateRebcontre daterebcontre DATETIME NOT NULL');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY reservation_ibfk_1');
        $this->addSql('DROP INDEX Choixterrain ON reservation');
        $this->addSql('ALTER TABLE reservation CHANGE DateReservation datereservation VARCHAR(255) NOT NULL, CHANGE Note note VARCHAR(255) NOT NULL, CHANGE Emplacement emplacement VARCHAR(255) NOT NULL, CHANGE idTerrain idterrain_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849554784967B FOREIGN KEY (idterrain_id) REFERENCES terrain (id)');
        $this->addSql('CREATE INDEX IDX_42C849554784967B ON reservation (idterrain_id)');
        $this->addSql('ALTER TABLE terrain CHANGE adresse adresse VARCHAR(255) NOT NULL, CHANGE description description VARCHAR(255) NOT NULL, CHANGE geo_x geo_x DOUBLE PRECISION NOT NULL, CHANGE geo_y geo_y DOUBLE PRECISION NOT NULL, CHANGE ouverture ouverture DATETIME NOT NULL, CHANGE fermeture fermeture DATETIME NOT NULL, CHANGE datedispo datedispo VARCHAR(255) NOT NULL');
        $this->addSql('DROP INDEX idCandidatV ON vote');
        $this->addSql('DROP INDEX idElectionV ON vote');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE messenger_messages');
        $this->addSql('ALTER TABLE article DROP FOREIGN KEY FK_23A0E66A44D9016');
        $this->addSql('DROP INDEX IDX_23A0E66A44D9016 ON article');
        $this->addSql('ALTER TABLE article ADD IdJournaliste INT NOT NULL, DROP idjournaliste_id, CHANGE description Description VARCHAR(1100) NOT NULL');
        $this->addSql('ALTER TABLE article ADD CONSTRAINT const FOREIGN KEY (IdJournaliste) REFERENCES user (idUser)');
        $this->addSql('CREATE INDEX const ON article (IdJournaliste)');
        $this->addSql('ALTER TABLE billet DROP FOREIGN KEY FK_1F034AF62A537711');
        $this->addSql('DROP INDEX IDX_1F034AF62A537711 ON billet');
        $this->addSql('ALTER TABLE billet ADD IdRencontre INT NOT NULL, DROP idrencontre_id');
        $this->addSql('ALTER TABLE billet ADD CONSTRAINT billet_ibfk_1 FOREIGN KEY (IdRencontre) REFERENCES rencontre (IdRencontre) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX Billet ON billet (IdRencontre)');
        $this->addSql('ALTER TABLE candidat DROP FOREIGN KEY FK_6AB5B47161C9AA50');
        $this->addSql('DROP INDEX IDX_6AB5B47161C9AA50 ON candidat');
        $this->addSql('ALTER TABLE candidat ADD idElection INT NOT NULL, DROP idelection_id');
        $this->addSql('ALTER TABLE candidat ADD CONSTRAINT candidat_ibfk_1 FOREIGN KEY (idElection) REFERENCES evenement (idE) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX idElection ON candidat (idElection)');
        $this->addSql('ALTER TABLE commande DROP FOREIGN KEY FK_6EEAA67DC29D63C1');
        $this->addSql('DROP INDEX IDX_6EEAA67DC29D63C1 ON commande');
        $this->addSql('ALTER TABLE commande ADD idproduit INT NOT NULL, DROP idproduit_id');
        $this->addSql('ALTER TABLE commande ADD CONSTRAINT commande_ibfk_1 FOREIGN KEY (idproduit) REFERENCES produit (IdProduit) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX commande_ibfk_1 ON commande (idproduit)');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_603499938C03F15C');
        $this->addSql('ALTER TABLE contrat DROP FOREIGN KEY FK_603499938C03F15C');
        $this->addSql('ALTER TABLE contrat CHANGE date_debut date_debut DATE DEFAULT NULL, CHANGE date_fin date_fin DATE DEFAULT NULL');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT contrat_ibfk_1 FOREIGN KEY (employee_id) REFERENCES joueur (id) ON UPDATE NO ACTION ON DELETE CASCADE');
        $this->addSql('DROP INDEX idx_603499938c03f15c ON contrat');
        $this->addSql('CREATE INDEX employee_id ON contrat (employee_id)');
        $this->addSql('ALTER TABLE contrat ADD CONSTRAINT FK_603499938C03F15C FOREIGN KEY (employee_id) REFERENCES joueur (id)');
        $this->addSql('ALTER TABLE evenement CHANGE datee dateE DATE NOT NULL');
        $this->addSql('ALTER TABLE joueur CHANGE imagepath imagePath VARCHAR(255) DEFAULT NULL, CHANGE link Link VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE produit DROP FOREIGN KEY FK_29A5EC2712469DE2');
        $this->addSql('DROP INDEX IDX_29A5EC2712469DE2 ON produit');
        $this->addSql('ALTER TABLE produit DROP category_id, CHANGE tailleproduit TailleProduit VARCHAR(5) NOT NULL');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404786A81FB');
        $this->addSql('DROP INDEX IDX_CE606404786A81FB ON reclamation');
        $this->addSql('ALTER TABLE reclamation ADD IdUser INT NOT NULL, DROP iduser_id, CHANGE description Description VARCHAR(1000) NOT NULL');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT reclamation_ibfk_1 FOREIGN KEY (IdUser) REFERENCES user (idUser) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX reclamation_ibfk_1 ON reclamation (IdUser)');
        $this->addSql('ALTER TABLE rencontre CHANGE daterebcontre DateRebcontre DATE NOT NULL');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849554784967B');
        $this->addSql('DROP INDEX IDX_42C849554784967B ON reservation');
        $this->addSql('ALTER TABLE reservation CHANGE datereservation DateReservation VARCHAR(255) DEFAULT NULL, CHANGE note Note VARCHAR(255) DEFAULT NULL, CHANGE emplacement Emplacement VARCHAR(255) DEFAULT NULL, CHANGE idterrain_id idTerrain INT DEFAULT NULL');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT reservation_ibfk_1 FOREIGN KEY (idTerrain) REFERENCES terrain (id) ON UPDATE CASCADE ON DELETE CASCADE');
        $this->addSql('CREATE INDEX Choixterrain ON reservation (idTerrain)');
        $this->addSql('ALTER TABLE terrain CHANGE adresse adresse VARCHAR(255) DEFAULT NULL, CHANGE description description TEXT DEFAULT NULL, CHANGE geo_x geo_x DOUBLE PRECISION DEFAULT NULL, CHANGE geo_y geo_y DOUBLE PRECISION DEFAULT NULL, CHANGE ouverture ouverture TIME DEFAULT NULL, CHANGE fermeture fermeture TIME DEFAULT NULL, CHANGE datedispo datedispo VARCHAR(255) DEFAULT NULL');
        $this->addSql('CREATE INDEX idCandidatV ON vote (idCandidatV)');
        $this->addSql('CREATE INDEX idElectionV ON vote (idElectionV)');
    }
}
