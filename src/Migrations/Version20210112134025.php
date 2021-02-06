<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210112134025 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE categoriespages (id INT AUTO_INCREMENT NOT NULL, tree_root INT DEFAULT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, lft INT NOT NULL, lvl INT NOT NULL, rgt INT NOT NULL, ordre INT DEFAULT 0 NOT NULL, INDEX IDX_A562CDE8A977936C (tree_root), INDEX IDX_A562CDE8727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pages_categories (pages_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_533F7E1B401ADD27 (pages_id), INDEX IDX_533F7E1BA21214B7 (categories_id), PRIMARY KEY(pages_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE categoriespages ADD CONSTRAINT FK_A562CDE8A977936C FOREIGN KEY (tree_root) REFERENCES categoriespages (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE categoriespages ADD CONSTRAINT FK_A562CDE8727ACA70 FOREIGN KEY (parent_id) REFERENCES categoriespages (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE pages_categories ADD CONSTRAINT FK_533F7E1B401ADD27 FOREIGN KEY (pages_id) REFERENCES pages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pages_categories ADD CONSTRAINT FK_533F7E1BA21214B7 FOREIGN KEY (categories_id) REFERENCES categoriespages (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE categoriespages DROP FOREIGN KEY FK_A562CDE8A977936C');
        $this->addSql('ALTER TABLE categoriespages DROP FOREIGN KEY FK_A562CDE8727ACA70');
        $this->addSql('ALTER TABLE pages_categories DROP FOREIGN KEY FK_533F7E1BA21214B7');
        $this->addSql('DROP TABLE categoriespages');
        $this->addSql('DROP TABLE pages_categories');
    }
}
