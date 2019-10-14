<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191013134748 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE logs (id INT AUTO_INCREMENT NOT NULL, log_id INT NOT NULL, ann_id INT NOT NULL, ann_date DATETIME NOT NULL, ann_auteur VARCHAR(100) NOT NULL, ann_titre VARCHAR(100) NOT NULL, ann_contenu LONGTEXT NOT NULL, log_date DATETIME NOT NULL, mod_type VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE annonces (id INT AUTO_INCREMENT NOT NULL, ann_id INT NOT NULL, ann_date DATETIME NOT NULL, ann_auteur VARCHAR(100) NOT NULL, ann_titre VARCHAR(100) NOT NULL, ann_contenu LONGTEXT NOT NULL, auteur_id INT NOT NULL, ann_a_valider TINYINT(1) NOT NULL, ann_signaler TINYINT(1) NOT NULL, ann_moderer TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE logs');
        $this->addSql('DROP TABLE annonces');
    }
}
