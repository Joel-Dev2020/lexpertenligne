<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210113103739 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE Formations (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, categories_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, cover VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) DEFAULT NULL, online TINYINT(1) NOT NULL, extrait LONGTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, view INT DEFAULT NULL, publeshed_at VARCHAR(30) DEFAULT NULL, featured TINYINT(1) NOT NULL, INDEX IDX_FCD22E7A76ED395 (user_id), INDEX IDX_FCD22E7A21214B7 (categories_id), FULLTEXT INDEX IDX_FCD22E75E237E06C4B5D954FEC530A9 (name, extrait, content), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categoriesformations (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(190) NOT NULL, slug VARCHAR(225) NOT NULL, ordre INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentairesformations (id INT AUTO_INCREMENT NOT NULL, formations_id INT DEFAULT NULL, user_id INT DEFAULT NULL, enabled TINYINT(1) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_74240EA73BF5B0C2 (formations_id), INDEX IDX_74240EA7A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mediasformations (id INT AUTO_INCREMENT NOT NULL, formations_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_C633F2B33BF5B0C2 (formations_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE Formations ADD CONSTRAINT FK_FCD22E7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE Formations ADD CONSTRAINT FK_FCD22E7A21214B7 FOREIGN KEY (categories_id) REFERENCES categoriesformations (id)');
        $this->addSql('ALTER TABLE commentairesformations ADD CONSTRAINT FK_74240EA73BF5B0C2 FOREIGN KEY (formations_id) REFERENCES Formations (id)');
        $this->addSql('ALTER TABLE commentairesformations ADD CONSTRAINT FK_74240EA7A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mediasformations ADD CONSTRAINT FK_C633F2B33BF5B0C2 FOREIGN KEY (formations_id) REFERENCES Formations (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE commentairesformations DROP FOREIGN KEY FK_74240EA73BF5B0C2');
        $this->addSql('ALTER TABLE mediasformations DROP FOREIGN KEY FK_C633F2B33BF5B0C2');
        $this->addSql('ALTER TABLE Formations DROP FOREIGN KEY FK_FCD22E7A21214B7');
        $this->addSql('DROP TABLE Formations');
        $this->addSql('DROP TABLE categoriesformations');
        $this->addSql('DROP TABLE commentairesformations');
        $this->addSql('DROP TABLE mediasformations');
    }
}
