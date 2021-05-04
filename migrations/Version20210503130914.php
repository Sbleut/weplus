<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503130914 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matos DROP FOREIGN KEY FK_5C8B43E5E50D78C3');
        $this->addSql('CREATE TABLE matos_catego (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('DROP TABLE catego_matos');
        $this->addSql('DROP INDEX IDX_5C8B43E5E50D78C3 ON matos');
        $this->addSql('ALTER TABLE matos CHANGE matos_categorie_id matos_catego_id INT NOT NULL');
        $this->addSql('ALTER TABLE matos ADD CONSTRAINT FK_5C8B43E59F8371B4 FOREIGN KEY (matos_catego_id) REFERENCES matos_catego (id)');
        $this->addSql('CREATE INDEX IDX_5C8B43E59F8371B4 ON matos (matos_catego_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matos DROP FOREIGN KEY FK_5C8B43E59F8371B4');
        $this->addSql('CREATE TABLE catego_matos (id INT AUTO_INCREMENT NOT NULL, name_catego_matos VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('DROP TABLE matos_catego');
        $this->addSql('DROP INDEX IDX_5C8B43E59F8371B4 ON matos');
        $this->addSql('ALTER TABLE matos CHANGE matos_catego_id matos_categorie_id INT NOT NULL');
        $this->addSql('ALTER TABLE matos ADD CONSTRAINT FK_5C8B43E5E50D78C3 FOREIGN KEY (matos_categorie_id) REFERENCES catego_matos (id)');
        $this->addSql('CREATE INDEX IDX_5C8B43E5E50D78C3 ON matos (matos_categorie_id)');
    }
}
