<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240710143416 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal ADD details LONGTEXT DEFAULT NULL');
        $this->addSql('ALTER TABLE rapport_veterinaire ADD nourriture VARCHAR(100) NOT NULL, ADD grammage VARCHAR(100) NOT NULL, DROP detail');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE animal DROP details');
        $this->addSql('ALTER TABLE rapport_veterinaire ADD detail VARCHAR(50) NOT NULL, DROP nourriture, DROP grammage');
    }
}
