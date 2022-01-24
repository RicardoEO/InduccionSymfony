<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220120211053 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE likes_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE likes (id INT NOT NULL, is_like BOOLEAN NOT NULL, idUser INT NOT NULL, idComment INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_49CA4E7DFE6E88D7 ON likes (idUser)');
        $this->addSql('CREATE INDEX IDX_49CA4E7D84CD399E ON likes (idComment)');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7DFE6E88D7 FOREIGN KEY (idUser) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE likes ADD CONSTRAINT FK_49CA4E7D84CD399E FOREIGN KEY (idComment) REFERENCES comments (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE likes_id_seq CASCADE');
        $this->addSql('DROP TABLE likes');
    }
}
