<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210118220301 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE dossiers_tags (dossiers_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_8B773B37651855E8 (dossiers_id), INDEX IDX_8B773B378D7B4FB4 (tags_id), PRIMARY KEY(dossiers_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formations_tags (formations_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_347CBF053BF5B0C2 (formations_id), INDEX IDX_347CBF058D7B4FB4 (tags_id), PRIMARY KEY(formations_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE blogs_tags (blogs_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_B21862B889C05BBC (blogs_id), INDEX IDX_B21862B88D7B4FB4 (tags_id), PRIMARY KEY(blogs_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pages_tags (pages_id INT NOT NULL, tags_id INT NOT NULL, INDEX IDX_2476DEA6401ADD27 (pages_id), INDEX IDX_2476DEA68D7B4FB4 (tags_id), PRIMARY KEY(pages_id, tags_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dossiers_tags ADD CONSTRAINT FK_8B773B37651855E8 FOREIGN KEY (dossiers_id) REFERENCES Dossiers (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dossiers_tags ADD CONSTRAINT FK_8B773B378D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formations_tags ADD CONSTRAINT FK_347CBF053BF5B0C2 FOREIGN KEY (formations_id) REFERENCES Formations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formations_tags ADD CONSTRAINT FK_347CBF058D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blogs_tags ADD CONSTRAINT FK_B21862B889C05BBC FOREIGN KEY (blogs_id) REFERENCES blogs (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blogs_tags ADD CONSTRAINT FK_B21862B88D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pages_tags ADD CONSTRAINT FK_2476DEA6401ADD27 FOREIGN KEY (pages_id) REFERENCES pages (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pages_tags ADD CONSTRAINT FK_2476DEA68D7B4FB4 FOREIGN KEY (tags_id) REFERENCES tags (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE formations CHANGE niveau niveau INT NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE dossiers_tags');
        $this->addSql('DROP TABLE formations_tags');
        $this->addSql('DROP TABLE blogs_tags');
        $this->addSql('DROP TABLE pages_tags');
        $this->addSql('ALTER TABLE Formations CHANGE niveau niveau INT NOT NULL');
    }
}
