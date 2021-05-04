<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210503132843 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matos ADD matos_image VARCHAR(255) NOT NULL, ADD matos_image_alt VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE matos_catego ADD matos_catego_image VARCHAR(255) NOT NULL, ADD matos_catego_image_alt VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE matos DROP matos_image, DROP matos_image_alt');
        $this->addSql('ALTER TABLE matos_catego DROP matos_catego_image, DROP matos_catego_image_alt');
    }
}
