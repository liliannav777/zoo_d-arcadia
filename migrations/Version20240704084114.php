<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240704084114 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE animal (animal_id INT AUTO_INCREMENT NOT NULL, prenom VARCHAR(50) NOT NULL, etat VARCHAR(50) NOT NULL, PRIMARY KEY(animal_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rapport_veterinaire DROP FOREIGN KEY FK_CE729CDEF85E0677');
        $this->addSql('DROP INDEX IDX_CE729CDEF85E0677 ON rapport_veterinaire');
        $this->addSql('ALTER TABLE rapport_veterinaire ADD animal_id INT NOT NULL, DROP username');
        $this->addSql('ALTER TABLE rapport_veterinaire ADD CONSTRAINT FK_CE729CDE8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (animal_id)');
        $this->addSql('CREATE INDEX IDX_CE729CDE8E962C16 ON rapport_veterinaire (animal_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rapport_veterinaire DROP FOREIGN KEY FK_CE729CDE8E962C16');
        $this->addSql('DROP TABLE animal');
        $this->addSql('DROP INDEX IDX_CE729CDE8E962C16 ON rapport_veterinaire');
        $this->addSql('ALTER TABLE rapport_veterinaire ADD username VARCHAR(50) DEFAULT NULL, DROP animal_id');
        $this->addSql('ALTER TABLE rapport_veterinaire ADD CONSTRAINT FK_CE729CDEF85E0677 FOREIGN KEY (username) REFERENCES utilisateur (username)');
        $this->addSql('CREATE INDEX IDX_CE729CDEF85E0677 ON rapport_veterinaire (username)');
    }
}
