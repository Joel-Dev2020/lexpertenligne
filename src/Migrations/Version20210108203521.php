<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210108203521 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE abonnes (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, active TINYINT(1) DEFAULT \'0\' NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE adresses (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, adresse VARCHAR(255) NOT NULL, email VARCHAR(180) NOT NULL, created_at DATETIME NOT NULL, nomprenoms VARCHAR(255) NOT NULL, contacts VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_EF192552E7927C74 (email), INDEX IDX_EF192552A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE approvisionnements (id INT AUTO_INCREMENT NOT NULL, products_id INT DEFAULT NULL, user_id INT DEFAULT NULL, oldqty INT NOT NULL, newqty INT NOT NULL, created_at DATETIME NOT NULL, remarque VARCHAR(500) DEFAULT NULL, INDEX IDX_2D5CE9C26C8A81A9 (products_id), INDEX IDX_2D5CE9C2A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blogs (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, categories_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, cover VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) DEFAULT NULL, online TINYINT(1) NOT NULL, extrait LONGTEXT DEFAULT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, view INT DEFAULT NULL, publeshed_at VARCHAR(30) DEFAULT NULL, featured TINYINT(1) NOT NULL, INDEX IDX_F41BCA70A76ED395 (user_id), INDEX IDX_F41BCA70A21214B7 (categories_id), FULLTEXT INDEX IDX_F41BCA705E237E06C4B5D954FEC530A9 (name, extrait, content), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categories (id INT AUTO_INCREMENT NOT NULL, tree_root INT DEFAULT NULL, parent_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, filename VARCHAR(255) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, lft INT NOT NULL, lvl INT NOT NULL, rgt INT NOT NULL, ordre INT DEFAULT 0 NOT NULL, INDEX IDX_3AF34668A977936C (tree_root), INDEX IDX_3AF34668727ACA70 (parent_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE categoriesblogs (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(190) NOT NULL, slug VARCHAR(225) NOT NULL, ordre INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commandes (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, status_id INT DEFAULT NULL, reference INT NOT NULL, created_at DATETIME NOT NULL, date DATE NOT NULL, totalht INT DEFAULT 0 NOT NULL, totaltva INT DEFAULT 0 NOT NULL, totalttc INT DEFAULT 0 NOT NULL, valider TINYINT(1) NOT NULL, products LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', adresses LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', modelivraison VARCHAR(255) DEFAULT NULL, pointrelais VARCHAR(255) DEFAULT NULL, chiffreenlettre VARCHAR(500) NOT NULL, notification TINYINT(1) NOT NULL, note VARCHAR(255) DEFAULT NULL, modepaiment VARCHAR(255) DEFAULT NULL, motifs VARCHAR(255) DEFAULT NULL, INDEX IDX_35D4282CA76ED395 (user_id), INDEX IDX_35D4282C6BF700BD (status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentaireproducts (id INT AUTO_INCREMENT NOT NULL, products_id INT DEFAULT NULL, user_id INT DEFAULT NULL, enabled TINYINT(1) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_B43A29746C8A81A9 (products_id), INDEX IDX_B43A2974A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE commentairesblogs (id INT AUTO_INCREMENT NOT NULL, blogs_id INT DEFAULT NULL, user_id INT DEFAULT NULL, enabled TINYINT(1) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_1F9C72189C05BBC (blogs_id), INDEX IDX_1F9C721A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE contacts (id INT AUTO_INCREMENT NOT NULL, nomprenoms VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, telephone VARCHAR(255) NOT NULL, sujet VARCHAR(255) NOT NULL, message LONGTEXT NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE logs (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, content VARCHAR(500) NOT NULL, action VARCHAR(255) NOT NULL, color VARCHAR(20) NOT NULL, icon VARCHAR(255) DEFAULT \'NULL\', created_at DATETIME NOT NULL, INDEX IDX_F08FC65CA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mediasblogs (id INT AUTO_INCREMENT NOT NULL, blogs_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_9B6CCB8C89C05BBC (blogs_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mediasproducts (id INT AUTO_INCREMENT NOT NULL, products_id INT DEFAULT NULL, name VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) NOT NULL, updated_at DATETIME DEFAULT NULL, INDEX IDX_D81E055F6C8A81A9 (products_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE metakeywords (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE notifications (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, contacts_id INT DEFAULT NULL, abonnes_id INT DEFAULT NULL, commandes_id INT DEFAULT NULL, titre VARCHAR(100) NOT NULL, action VARCHAR(100) NOT NULL, created_at DATETIME NOT NULL, icon VARCHAR(20) NOT NULL, color VARCHAR(30) NOT NULL, reading TINYINT(1) NOT NULL, INDEX IDX_6000B0D3A76ED395 (user_id), INDEX IDX_6000B0D3719FB48E (contacts_id), INDEX IDX_6000B0D3FEDEAEF2 (abonnes_id), INDEX IDX_6000B0D38BF5C2E6 (commandes_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pages (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, slug VARCHAR(100) NOT NULL, filename VARCHAR(255) DEFAULT NULL, content LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, online TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_2074E575989D9B62 (slug), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE parametres (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, keywords LONGTEXT DEFAULT NULL, telephone VARCHAR(30) DEFAULT NULL, cellulaire VARCHAR(30) DEFAULT NULL, email VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) DEFAULT NULL, filename2 VARCHAR(255) DEFAULT NULL, filename3 VARCHAR(255) DEFAULT NULL, adresses LONGTEXT DEFAULT NULL, updated_at DATETIME DEFAULT NULL, activite VARCHAR(255) DEFAULT NULL, slogan VARCHAR(255) DEFAULT NULL, facebook VARCHAR(255) DEFAULT NULL, instagram VARCHAR(255) DEFAULT NULL, twitter VARCHAR(255) DEFAULT NULL, tva INT DEFAULT 0 NOT NULL, seuilproduct INT DEFAULT 0 NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, slug VARCHAR(255) NOT NULL, description LONGTEXT DEFAULT NULL, metadescription LONGTEXT DEFAULT NULL, filename VARCHAR(255) DEFAULT NULL, filenamehover VARCHAR(255) DEFAULT NULL, price INT DEFAULT 0 NOT NULL, pricepromo INT DEFAULT 0, quantity INT DEFAULT 0 NOT NULL, weight VARCHAR(255) DEFAULT NULL, created_at DATETIME DEFAULT NULL, updated_at DATETIME DEFAULT NULL, online TINYINT(1) DEFAULT \'0\', extrait VARCHAR(500) DEFAULT NULL, featured TINYINT(1) DEFAULT \'0\', nouveau TINYINT(1) DEFAULT \'0\', vues INT DEFAULT 0 NOT NULL, published_at DATETIME DEFAULT NULL, delaislivraison VARCHAR(255) DEFAULT NULL, garantie VARCHAR(255) DEFAULT NULL, videourl VARCHAR(255) DEFAULT NULL, payement_at DATETIME DEFAULT NULL, sku VARCHAR(30) DEFAULT NULL, INDEX IDX_B3BA5A5AA76ED395 (user_id), FULLTEXT INDEX IDX_B3BA5A5A5E237E06C4B5D9546DE44026 (name, extrait, description), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products_metakeywords (products_id INT NOT NULL, metakeywords_id INT NOT NULL, INDEX IDX_F04BDC6F6C8A81A9 (products_id), INDEX IDX_F04BDC6FA1A5826D (metakeywords_id), PRIMARY KEY(products_id, metakeywords_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products_products (products_source INT NOT NULL, products_target INT NOT NULL, INDEX IDX_A6BB4AE9D9B9F459 (products_source), INDEX IDX_A6BB4AE9C05CA4D6 (products_target), PRIMARY KEY(products_source, products_target)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE products_categories (products_id INT NOT NULL, categories_id INT NOT NULL, INDEX IDX_E8ACBE766C8A81A9 (products_id), INDEX IDX_E8ACBE76A21214B7 (categories_id), PRIMARY KEY(products_id, categories_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE publicites (id INT AUTO_INCREMENT NOT NULL, pubblock1 VARCHAR(255) DEFAULT NULL, filenamepubblock1 VARCHAR(255) DEFAULT NULL, urlpubblock1 VARCHAR(255) DEFAULT NULL, pubblock2 VARCHAR(255) DEFAULT NULL, filenamepubblock2 VARCHAR(255) DEFAULT NULL, urlpubblock2 VARCHAR(255) DEFAULT NULL, pubblock3 VARCHAR(255) DEFAULT NULL, filenamepubblock3 VARCHAR(255) DEFAULT NULL, urlpubblock3 VARCHAR(255) DEFAULT NULL, onlinepub1 TINYINT(1) DEFAULT NULL, onlinepub2 TINYINT(1) DEFAULT NULL, onlinepub3 TINYINT(1) DEFAULT NULL, pubblock4 VARCHAR(255) DEFAULT NULL, filenamepubblock4 VARCHAR(255) DEFAULT NULL, urlpubblock4 VARCHAR(255) DEFAULT NULL, onlinepub4 TINYINT(1) DEFAULT NULL, pubblock5 VARCHAR(255) DEFAULT NULL, filenamepubblock5 VARCHAR(255) DEFAULT NULL, urlpubblock5 VARCHAR(255) DEFAULT NULL, onlinepub5 TINYINT(1) DEFAULT NULL, updated_at DATETIME DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT DEFAULT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, color VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7B00651C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE tags (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, slug VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\', password VARCHAR(255) NOT NULL, username VARCHAR(255) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenoms VARCHAR(255) DEFAULT NULL, contacts VARCHAR(50) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, filename VARCHAR(255) DEFAULT NULL, enabled TINYINT(1) DEFAULT \'0\' NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME DEFAULT NULL, last_login DATETIME DEFAULT NULL, last_activity DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE wishlists (id INT AUTO_INCREMENT NOT NULL, products_id INT DEFAULT NULL, user_id INT DEFAULT NULL, created_at DATETIME NOT NULL, INDEX IDX_4A4C2E1B6C8A81A9 (products_id), INDEX IDX_4A4C2E1BA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE adresses ADD CONSTRAINT FK_EF192552A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE approvisionnements ADD CONSTRAINT FK_2D5CE9C26C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE approvisionnements ADD CONSTRAINT FK_2D5CE9C2A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE blogs ADD CONSTRAINT FK_F41BCA70A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE blogs ADD CONSTRAINT FK_F41BCA70A21214B7 FOREIGN KEY (categories_id) REFERENCES categoriesblogs (id)');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668A977936C FOREIGN KEY (tree_root) REFERENCES categories (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE categories ADD CONSTRAINT FK_3AF34668727ACA70 FOREIGN KEY (parent_id) REFERENCES categories (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commandes ADD CONSTRAINT FK_35D4282C6BF700BD FOREIGN KEY (status_id) REFERENCES status (id)');
        $this->addSql('ALTER TABLE commentaireproducts ADD CONSTRAINT FK_B43A29746C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE commentaireproducts ADD CONSTRAINT FK_B43A2974A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE commentairesblogs ADD CONSTRAINT FK_1F9C72189C05BBC FOREIGN KEY (blogs_id) REFERENCES blogs (id)');
        $this->addSql('ALTER TABLE commentairesblogs ADD CONSTRAINT FK_1F9C721A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE logs ADD CONSTRAINT FK_F08FC65CA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE mediasblogs ADD CONSTRAINT FK_9B6CCB8C89C05BBC FOREIGN KEY (blogs_id) REFERENCES blogs (id)');
        $this->addSql('ALTER TABLE mediasproducts ADD CONSTRAINT FK_D81E055F6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3719FB48E FOREIGN KEY (contacts_id) REFERENCES contacts (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D3FEDEAEF2 FOREIGN KEY (abonnes_id) REFERENCES abonnes (id)');
        $this->addSql('ALTER TABLE notifications ADD CONSTRAINT FK_6000B0D38BF5C2E6 FOREIGN KEY (commandes_id) REFERENCES commandes (id)');
        $this->addSql('ALTER TABLE products ADD CONSTRAINT FK_B3BA5A5AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE products_metakeywords ADD CONSTRAINT FK_F04BDC6F6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_metakeywords ADD CONSTRAINT FK_F04BDC6FA1A5826D FOREIGN KEY (metakeywords_id) REFERENCES metakeywords (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_products ADD CONSTRAINT FK_A6BB4AE9D9B9F459 FOREIGN KEY (products_source) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_products ADD CONSTRAINT FK_A6BB4AE9C05CA4D6 FOREIGN KEY (products_target) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_categories ADD CONSTRAINT FK_E8ACBE766C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE products_categories ADD CONSTRAINT FK_E8ACBE76A21214B7 FOREIGN KEY (categories_id) REFERENCES categories (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE wishlists ADD CONSTRAINT FK_4A4C2E1B6C8A81A9 FOREIGN KEY (products_id) REFERENCES products (id)');
        $this->addSql('ALTER TABLE wishlists ADD CONSTRAINT FK_4A4C2E1BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3FEDEAEF2');
        $this->addSql('ALTER TABLE commentairesblogs DROP FOREIGN KEY FK_1F9C72189C05BBC');
        $this->addSql('ALTER TABLE mediasblogs DROP FOREIGN KEY FK_9B6CCB8C89C05BBC');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668A977936C');
        $this->addSql('ALTER TABLE categories DROP FOREIGN KEY FK_3AF34668727ACA70');
        $this->addSql('ALTER TABLE products_categories DROP FOREIGN KEY FK_E8ACBE76A21214B7');
        $this->addSql('ALTER TABLE blogs DROP FOREIGN KEY FK_F41BCA70A21214B7');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D38BF5C2E6');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3719FB48E');
        $this->addSql('ALTER TABLE products_metakeywords DROP FOREIGN KEY FK_F04BDC6FA1A5826D');
        $this->addSql('ALTER TABLE approvisionnements DROP FOREIGN KEY FK_2D5CE9C26C8A81A9');
        $this->addSql('ALTER TABLE commentaireproducts DROP FOREIGN KEY FK_B43A29746C8A81A9');
        $this->addSql('ALTER TABLE mediasproducts DROP FOREIGN KEY FK_D81E055F6C8A81A9');
        $this->addSql('ALTER TABLE products_metakeywords DROP FOREIGN KEY FK_F04BDC6F6C8A81A9');
        $this->addSql('ALTER TABLE products_products DROP FOREIGN KEY FK_A6BB4AE9D9B9F459');
        $this->addSql('ALTER TABLE products_products DROP FOREIGN KEY FK_A6BB4AE9C05CA4D6');
        $this->addSql('ALTER TABLE products_categories DROP FOREIGN KEY FK_E8ACBE766C8A81A9');
        $this->addSql('ALTER TABLE wishlists DROP FOREIGN KEY FK_4A4C2E1B6C8A81A9');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282C6BF700BD');
        $this->addSql('ALTER TABLE adresses DROP FOREIGN KEY FK_EF192552A76ED395');
        $this->addSql('ALTER TABLE approvisionnements DROP FOREIGN KEY FK_2D5CE9C2A76ED395');
        $this->addSql('ALTER TABLE blogs DROP FOREIGN KEY FK_F41BCA70A76ED395');
        $this->addSql('ALTER TABLE commandes DROP FOREIGN KEY FK_35D4282CA76ED395');
        $this->addSql('ALTER TABLE commentaireproducts DROP FOREIGN KEY FK_B43A2974A76ED395');
        $this->addSql('ALTER TABLE commentairesblogs DROP FOREIGN KEY FK_1F9C721A76ED395');
        $this->addSql('ALTER TABLE logs DROP FOREIGN KEY FK_F08FC65CA76ED395');
        $this->addSql('ALTER TABLE notifications DROP FOREIGN KEY FK_6000B0D3A76ED395');
        $this->addSql('ALTER TABLE products DROP FOREIGN KEY FK_B3BA5A5AA76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE wishlists DROP FOREIGN KEY FK_4A4C2E1BA76ED395');
        $this->addSql('DROP TABLE abonnes');
        $this->addSql('DROP TABLE adresses');
        $this->addSql('DROP TABLE approvisionnements');
        $this->addSql('DROP TABLE blogs');
        $this->addSql('DROP TABLE categories');
        $this->addSql('DROP TABLE categoriesblogs');
        $this->addSql('DROP TABLE commandes');
        $this->addSql('DROP TABLE commentaireproducts');
        $this->addSql('DROP TABLE commentairesblogs');
        $this->addSql('DROP TABLE contacts');
        $this->addSql('DROP TABLE logs');
        $this->addSql('DROP TABLE mediasblogs');
        $this->addSql('DROP TABLE mediasproducts');
        $this->addSql('DROP TABLE metakeywords');
        $this->addSql('DROP TABLE notifications');
        $this->addSql('DROP TABLE pages');
        $this->addSql('DROP TABLE parametres');
        $this->addSql('DROP TABLE products');
        $this->addSql('DROP TABLE products_metakeywords');
        $this->addSql('DROP TABLE products_products');
        $this->addSql('DROP TABLE products_categories');
        $this->addSql('DROP TABLE publicites');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE tags');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE wishlists');
    }
}
