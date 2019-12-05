<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191024135129 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE logs');
        $this->addSql('ALTER TABLE annonces ADD ann_supprimee TINYINT(1) NOT NULL, ADD ann_moderee TINYINT(1) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE logs (id INT AUTO_INCREMENT NOT NULL, ann_id INT NOT NULL, ann_date DATETIME NOT NULL, ann_auteur VARCHAR(50) NOT NULL COLLATE utf8mb4_unicode_ci, ann_titre VARCHAR(100) NOT NULL COLLATE utf8mb4_unicode_ci, ann_contenu LONGTEXT NOT NULL COLLATE utf8mb4_unicode_ci, log_date DATETIME NOT NULL, ann_deleted TINYINT(1) NOT NULL, ann_moderated TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE annonces DROP ann_supprimee, DROP ann_moderee');
    }
}
