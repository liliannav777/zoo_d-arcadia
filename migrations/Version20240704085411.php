<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240704085411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE utilisateur_role (username VARCHAR(50) NOT NULL, role_id INT NOT NULL, INDEX IDX_9EE8E650F85E0677 (username), INDEX IDX_9EE8E650D60322AC (role_id), PRIMARY KEY(username, role_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE utilisateur_role ADD CONSTRAINT FK_9EE8E650F85E0677 FOREIGN KEY (username) REFERENCES utilisateur (username)');
        $this->addSql('ALTER TABLE utilisateur_role ADD CONSTRAINT FK_9EE8E650D60322AC FOREIGN KEY (role_id) REFERENCES role (role_id)');
        $this->addSql('ALTER TABLE rapport_veterinaire ADD username VARCHAR(50) NOT NULL');
        $this->addSql('ALTER TABLE rapport_veterinaire ADD CONSTRAINT FK_CE729CDEF85E0677 FOREIGN KEY (username) REFERENCES utilisateur (username)');
        $this->addSql('CREATE INDEX IDX_CE729CDEF85E0677 ON rapport_veterinaire (username)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE utilisateur_role DROP FOREIGN KEY FK_9EE8E650F85E0677');
        $this->addSql('ALTER TABLE utilisateur_role DROP FOREIGN KEY FK_9EE8E650D60322AC');
        $this->addSql('DROP TABLE utilisateur_role');
        $this->addSql('ALTER TABLE rapport_veterinaire DROP FOREIGN KEY FK_CE729CDEF85E0677');
        $this->addSql('DROP INDEX IDX_CE729CDEF85E0677 ON rapport_veterinaire');
        $this->addSql('ALTER TABLE rapport_veterinaire DROP username');
    }
}
