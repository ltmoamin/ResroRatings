<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250301124110 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE achat (idachat INT AUTO_INCREMENT NOT NULL, iduser INT DEFAULT NULL, idplat INT DEFAULT NULL, montanttotal DOUBLE PRECISION NOT NULL, quantite INT NOT NULL, date DATE NOT NULL, type VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_26A984565E5C27E9 (iduser), UNIQUE INDEX UNIQ_26A98456F3F753A7 (idplat), PRIMARY KEY(idachat)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE avis (id INT AUTO_INCREMENT NOT NULL, iduser INT DEFAULT NULL, id_restau INT DEFAULT NULL, pubavis VARCHAR(255) NOT NULL, titreavis VARCHAR(255) NOT NULL, dateavis DATE NOT NULL, nbvue INT NOT NULL, INDEX IDX_8F91ABF05E5C27E9 (iduser), INDEX IDX_8F91ABF0F6365012 (id_restau), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE badge (id INT AUTO_INCREMENT NOT NULL, iduser INT DEFAULT NULL, id_restau INT DEFAULT NULL, commantaire VARCHAR(255) NOT NULL, datebadge DATE NOT NULL, typebadge VARCHAR(255) NOT NULL, likes INT NOT NULL, dislikes INT NOT NULL, INDEX IDX_FEF0481D5E5C27E9 (iduser), INDEX IDX_FEF0481DF6365012 (id_restau), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE evennement (idevent INT AUTO_INCREMENT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date DATE NOT NULL, img VARCHAR(255) NOT NULL, lieu VARCHAR(255) NOT NULL, adresse VARCHAR(255) NOT NULL, PRIMARY KEY(idevent)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE participant (idparticipant INT AUTO_INCREMENT NOT NULL, idevent INT DEFAULT NULL, iduser INT DEFAULT NULL, datepar DATE NOT NULL, numero INT NOT NULL, UNIQUE INDEX UNIQ_D79F6B11EDAB66BE (idevent), UNIQUE INDEX UNIQ_D79F6B115E5C27E9 (iduser), PRIMARY KEY(idparticipant)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE plat (idplat INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, image VARCHAR(255) NOT NULL, prix DOUBLE PRECISION NOT NULL, categorie VARCHAR(255) NOT NULL, PRIMARY KEY(idplat)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (idrec INT AUTO_INCREMENT NOT NULL, iduser INT DEFAULT NULL, date DATE NOT NULL, description VARCHAR(255) NOT NULL, typerec VARCHAR(255) NOT NULL, etatrec VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_CE6064045E5C27E9 (iduser), PRIMARY KEY(idrec)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reponse (idrep INT AUTO_INCREMENT NOT NULL, idrec INT DEFAULT NULL, contenue VARCHAR(255) NOT NULL, daterep DATE NOT NULL, UNIQUE INDEX UNIQ_5FB6DEC77D00914B (idrec), PRIMARY KEY(idrep)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reservation (id_res INT AUTO_INCREMENT NOT NULL, id_user INT DEFAULT NULL, id_restau INT DEFAULT NULL, datereser DATE NOT NULL, timereser TIME NOT NULL, UNIQUE INDEX UNIQ_42C849556B3CA4B (id_user), UNIQUE INDEX UNIQ_42C84955F6365012 (id_restau), PRIMARY KEY(id_res)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE restaurant (id_restau INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, location VARCHAR(255) NOT NULL, PRIMARY KEY(id_restau)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (iduser INT AUTO_INCREMENT NOT NULL, username VARCHAR(30) NOT NULL, email VARCHAR(180) NOT NULL, role JSON NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, firstname VARCHAR(30) NOT NULL, lastname VARCHAR(30) NOT NULL, tel VARCHAR(20) DEFAULT NULL, address VARCHAR(300) DEFAULT NULL, reset_token VARCHAR(180) NOT NULL, is_blocked TINYINT(1) NOT NULL, is_approved TINYINT(1) NOT NULL, etat VARCHAR(255) DEFAULT \'Actif\', status VARCHAR(255) DEFAULT \'Actif\' NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(iduser)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A984565E5C27E9 FOREIGN KEY (iduser) REFERENCES user (iduser)');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A98456F3F753A7 FOREIGN KEY (idplat) REFERENCES plat (idplat)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF05E5C27E9 FOREIGN KEY (iduser) REFERENCES user (iduser)');
        $this->addSql('ALTER TABLE avis ADD CONSTRAINT FK_8F91ABF0F6365012 FOREIGN KEY (id_restau) REFERENCES restaurant (id_restau)');
        $this->addSql('ALTER TABLE badge ADD CONSTRAINT FK_FEF0481D5E5C27E9 FOREIGN KEY (iduser) REFERENCES user (iduser)');
        $this->addSql('ALTER TABLE badge ADD CONSTRAINT FK_FEF0481DF6365012 FOREIGN KEY (id_restau) REFERENCES restaurant (id_restau)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B11EDAB66BE FOREIGN KEY (idevent) REFERENCES evennement (idevent)');
        $this->addSql('ALTER TABLE participant ADD CONSTRAINT FK_D79F6B115E5C27E9 FOREIGN KEY (iduser) REFERENCES user (iduser)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE6064045E5C27E9 FOREIGN KEY (iduser) REFERENCES user (iduser)');
        $this->addSql('ALTER TABLE reponse ADD CONSTRAINT FK_5FB6DEC77D00914B FOREIGN KEY (idrec) REFERENCES reclamation (idrec)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C849556B3CA4B FOREIGN KEY (id_user) REFERENCES user (iduser)');
        $this->addSql('ALTER TABLE reservation ADD CONSTRAINT FK_42C84955F6365012 FOREIGN KEY (id_restau) REFERENCES restaurant (id_restau)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A984565E5C27E9');
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A98456F3F753A7');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF05E5C27E9');
        $this->addSql('ALTER TABLE avis DROP FOREIGN KEY FK_8F91ABF0F6365012');
        $this->addSql('ALTER TABLE badge DROP FOREIGN KEY FK_FEF0481D5E5C27E9');
        $this->addSql('ALTER TABLE badge DROP FOREIGN KEY FK_FEF0481DF6365012');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B11EDAB66BE');
        $this->addSql('ALTER TABLE participant DROP FOREIGN KEY FK_D79F6B115E5C27E9');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE6064045E5C27E9');
        $this->addSql('ALTER TABLE reponse DROP FOREIGN KEY FK_5FB6DEC77D00914B');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C849556B3CA4B');
        $this->addSql('ALTER TABLE reservation DROP FOREIGN KEY FK_42C84955F6365012');
        $this->addSql('DROP TABLE achat');
        $this->addSql('DROP TABLE avis');
        $this->addSql('DROP TABLE badge');
        $this->addSql('DROP TABLE evennement');
        $this->addSql('DROP TABLE participant');
        $this->addSql('DROP TABLE plat');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE reponse');
        $this->addSql('DROP TABLE reservation');
        $this->addSql('DROP TABLE restaurant');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
