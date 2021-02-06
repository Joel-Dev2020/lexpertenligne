<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210115063607 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formations CHANGE niveau niveau INT NOT NULL');
        $this->addSql('ALTER TABLE pages ADD user_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pages ADD CONSTRAINT FK_2074E575A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_2074E575A76ED395 ON pages (user_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE Formations CHANGE niveau niveau INT NOT NULL');
        $this->addSql('ALTER TABLE pages DROP FOREIGN KEY FK_2074E575A76ED395');
        $this->addSql('DROP INDEX IDX_2074E575A76ED395 ON pages');
        $this->addSql('ALTER TABLE pages DROP user_id');
    }
}
