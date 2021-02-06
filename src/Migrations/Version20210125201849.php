<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210125201849 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE formations CHANGE niveau niveau INT NOT NULL');
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288FC893DE');
        $this->addSql('DROP INDEX IDX_A2B07288FC893DE ON documents');
        $this->addSql('ALTER TABLE documents CHANGE categoriesdocuments_id categories_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B07288A21214B7 FOREIGN KEY (categories_id) REFERENCES categoriesdocuments (id)');
        $this->addSql('CREATE INDEX IDX_A2B07288A21214B7 ON documents (categories_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE documents DROP FOREIGN KEY FK_A2B07288A21214B7');
        $this->addSql('DROP INDEX IDX_A2B07288A21214B7 ON documents');
        $this->addSql('ALTER TABLE documents CHANGE categories_id categoriesdocuments_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE documents ADD CONSTRAINT FK_A2B07288FC893DE FOREIGN KEY (categoriesdocuments_id) REFERENCES categoriesdocuments (id)');
        $this->addSql('CREATE INDEX IDX_A2B07288FC893DE ON documents (categoriesdocuments_id)');
        $this->addSql('ALTER TABLE Formations CHANGE niveau niveau INT NOT NULL');
    }
}
