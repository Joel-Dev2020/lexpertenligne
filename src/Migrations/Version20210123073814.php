<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210123073814 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE articles (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_BFDD3168989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE articles_categories (articles_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_DE004A0E1EBAF6CC (articles_id), INDEX IDX_DE004A0EA21214B7 (categories_id), PRIMARY KEY(articles_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categoriesarticles (id INT AUTO_INCREMENT NOT NULL, tree_root INT DEFAULT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, lft INT NOT NULL, lvl INT NOT NULL, rgt INT NOT NULL, ordre INT DEFAULT 0 NOT NULL, INDEX IDX_E39F01B5A977936C (tree_root), INDEX IDX_E39F01B5727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE articles_categories ADD CONSTRAINT FK_DE004A0E1EBAF6CC FOREIGN KEY (articles_id) REFERENCES articles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE articles_categories ADD CONSTRAINT FK_DE004A0EA21214B7 FOREIGN KEY (categories_id) REFERENCES categoriesarticles (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE categoriesarticles ADD CONSTRAINT FK_E39F01B5A977936C FOREIGN KEY (tree_root) REFERENCES categoriesarticles (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE categoriesarticles ADD CONSTRAINT FK_E39F01B5727ACA70 FOREIGN KEY (parent_id) REFERENCES categoriesarticles (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE formations CHANGE niveau niveau INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE articles_categories DROP FOREIGN KEY FK_DE004A0E1EBAF6CC');
        $this->addSql('ALTER TABLE articles_categories DROP FOREIGN KEY FK_DE004A0EA21214B7');
        $this->addSql('ALTER TABLE categoriesarticles DROP FOREIGN KEY FK_E39F01B5A977936C');
        $this->addSql('ALTER TABLE categoriesarticles DROP FOREIGN KEY FK_E39F01B5727ACA70');
        $this->addSql('DROP TABLE articles');
        $this->addSql('DROP TABLE articles_categories');
        $this->addSql('DROP TABLE categoriesarticles');
        $this->addSql('ALTER TABLE Formations CHANGE niveau niveau INT NOT NULL');
    }
}
