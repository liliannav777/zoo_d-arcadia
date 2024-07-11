<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240709161224 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE rapport_employe (id INT AUTO_INCREMENT NOT NULL, animal_id INT NOT NULL, username VARCHAR(50) NOT NULL, date DATETIME NOT NULL, nourriture VARCHAR(100) NOT NULL, quantite DOUBLE PRECISION NOT NULL, INDEX IDX_83D4B3DA8E962C16 (animal_id), INDEX IDX_83D4B3DAF85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE rapport_employe ADD CONSTRAINT FK_83D4B3DA8E962C16 FOREIGN KEY (animal_id) REFERENCES animal (animal_id)');
        $this->addSql('ALTER TABLE rapport_employe ADD CONSTRAINT FK_83D4B3DAF85E0677 FOREIGN KEY (username) REFERENCES utilisateur (username)');
        $this->addSql('ALTER TABLE habitat CHANGE commentaire_habitat commentaire_habitat VARCHAR(50) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE rapport_employe DROP FOREIGN KEY FK_83D4B3DA8E962C16');
        $this->addSql('ALTER TABLE rapport_employe DROP FOREIGN KEY FK_83D4B3DAF85E0677');
        $this->addSql('DROP TABLE rapport_employe');
        $this->addSql('ALTER TABLE habitat CHANGE commentaire_habitat commentaire_habitat VARCHAR(50) DEFAULT NULL');
    }
}
