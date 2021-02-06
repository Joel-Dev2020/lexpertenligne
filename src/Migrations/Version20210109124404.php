<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210109124404 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Dossiers (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, categories_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, cover VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) DEFAULT NULL, online TINYINT(1) NOT NULL, extrait LONGTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, view INT DEFAULT NULL, publeshed_at VARCHAR(30) DEFAULT NULL, featured TINYINT(1) NOT NULL, INDEX IDX_5AF840B2A76ED395 (user_id), INDEX IDX_5AF840B2A21214B7 (categories_id), FULLTEXT INDEX IDX_5AF840B25E237E06C4B5D954FEC530A9 (name, extrait, content), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categoriesdossiers (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(190) NOT NULL, slug VARCHAR(225) NOT NULL, ordre INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentairesdossiers (id INT AUTO_INCREMENT NOT NULL, dossiers_id INT DEFAULT NULL, user_id INT DEFAULT NULL, enabled TINYINT(1) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_355A9B8F651855E8 (dossiers_id), INDEX IDX_355A9B8FA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mediasdossiers (id INT AUTO_INCREMENT NOT NULL, dossiers_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C82A7DE1651855E8 (dossiers_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Dossiers ADD CONSTRAINT FK_5AF840B2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE Dossiers ADD CONSTRAINT FK_5AF840B2A21214B7 FOREIGN KEY (categories_id) REFERENCES categoriesdossiers (id)');
        $this->addSql('ALTER TABLE commentairesdossiers ADD CONSTRAINT FK_355A9B8F651855E8 FOREIGN KEY (dossiers_id) REFERENCES Dossiers (id)');
        $this->addSql('ALTER TABLE commentairesdossiers ADD CONSTRAINT FK_355A9B8FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mediasdossiers ADD CONSTRAINT FK_C82A7DE1651855E8 FOREIGN KEY (dossiers_id) REFERENCES Dossiers (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentairesdossiers DROP FOREIGN KEY FK_355A9B8F651855E8');
        $this->addSql('ALTER TABLE mediasdossiers DROP FOREIGN KEY FK_C82A7DE1651855E8');
        $this->addSql('ALTER TABLE Dossiers DROP FOREIGN KEY FK_5AF840B2A21214B7');
        $this->addSql('DROP TABLE Dossiers');
        $this->addSql('DROP TABLE categoriesdossiers');
        $this->addSql('DROP TABLE commentairesdossiers');
        $this->addSql('DROP TABLE mediasdossiers');
    }
}
