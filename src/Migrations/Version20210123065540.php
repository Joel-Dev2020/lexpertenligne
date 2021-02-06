<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210123065540 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dtarticles (id INT AUTO_INCREMENT NOT NULL, dtcategories_id INT DEFAULT NULL, dtparties_id INT DEFAULT NULL, dttitres_id INT DEFAULT NULL, dtchapitres_id INT DEFAULT NULL, dtsections_id INT DEFAULT NULL, numero_article VARCHAR(100) NOT NULL, contenu_article LONGTEXT NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, slug VARCHAR(255) NOT NULL, online TINYINT(1) DEFAULT NULL, keywords LONGTEXT DEFAULT NULL, INDEX IDX_4C36B0A441344D0C (dtcategories_id), INDEX IDX_4C36B0A4CBDA773C (dtparties_id), INDEX IDX_4C36B0A4F956EB62 (dttitres_id), INDEX IDX_4C36B0A465EB02F6 (dtchapitres_id), INDEX IDX_4C36B0A4DA8CFBC9 (dtsections_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dtcategories (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dtchapitres (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dtparties (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dtsections (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dttitres (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dtarticles ADD CONSTRAINT FK_4C36B0A441344D0C FOREIGN KEY (dtcategories_id) REFERENCES dtcategories (id)');
        $this->addSql('ALTER TABLE dtarticles ADD CONSTRAINT FK_4C36B0A4CBDA773C FOREIGN KEY (dtparties_id) REFERENCES dtparties (id)');
        $this->addSql('ALTER TABLE dtarticles ADD CONSTRAINT FK_4C36B0A4F956EB62 FOREIGN KEY (dttitres_id) REFERENCES dttitres (id)');
        $this->addSql('ALTER TABLE dtarticles ADD CONSTRAINT FK_4C36B0A465EB02F6 FOREIGN KEY (dtchapitres_id) REFERENCES dtchapitres (id)');
        $this->addSql('ALTER TABLE dtarticles ADD CONSTRAINT FK_4C36B0A4DA8CFBC9 FOREIGN KEY (dtsections_id) REFERENCES dtsections (id)');
        $this->addSql('ALTER TABLE formations CHANGE niveau niveau INT NOT NULL');
        $this->addSql('ALTER TABLE contacts ADD active TINYINT(1) DEFAULT \'0\' NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dtarticles DROP FOREIGN KEY FK_4C36B0A441344D0C');
        $this->addSql('ALTER TABLE dtarticles DROP FOREIGN KEY FK_4C36B0A465EB02F6');
        $this->addSql('ALTER TABLE dtarticles DROP FOREIGN KEY FK_4C36B0A4CBDA773C');
        $this->addSql('ALTER TABLE dtarticles DROP FOREIGN KEY FK_4C36B0A4DA8CFBC9');
        $this->addSql('ALTER TABLE dtarticles DROP FOREIGN KEY FK_4C36B0A4F956EB62');
        $this->addSql('DROP TABLE dtarticles');
        $this->addSql('DROP TABLE dtcategories');
        $this->addSql('DROP TABLE dtchapitres');
        $this->addSql('DROP TABLE dtparties');
        $this->addSql('DROP TABLE dtsections');
        $this->addSql('DROP TABLE dttitres');
        $this->addSql('ALTER TABLE contacts DROP active');
        $this->addSql('ALTER TABLE Formations CHANGE niveau niveau INT NOT NULL');
    }
}
