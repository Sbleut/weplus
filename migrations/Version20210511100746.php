<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210511100746 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE associations (id INT AUTO_INCREMENT NOT NULL, name_asso VARCHAR(255) NOT NULL, lien_asso VARCHAR(255) NOT NULL, reso_asso VARCHAR(255) NOT NULL, text_asso VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE associations_causes (associations_id INT NOT NULL, causes_id INT NOT NULL, INDEX IDX_D998BB594122538A (associations_id), INDEX IDX_D998BB591D186264 (causes_id), PRIMARY KEY(associations_id, causes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE causes (id INT AUTO_INCREMENT NOT NULL, nom_cause VARCHAR(255) NOT NULL, text_cause VARCHAR(255) NOT NULL, citation VARCHAR(255) NOT NULL, lien_video VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprises (id INT AUTO_INCREMENT NOT NULL, name_entreprise VARCHAR(255) NOT NULL, lien_entreprise VARCHAR(255) NOT NULL, logo VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprises_causes (entreprises_id INT NOT NULL, causes_id INT NOT NULL, INDEX IDX_AF2E9840A70A18EC (entreprises_id), INDEX IDX_AF2E98401D186264 (causes_id), PRIMARY KEY(entreprises_id, causes_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE associations_causes ADD CONSTRAINT FK_D998BB594122538A FOREIGN KEY (associations_id) REFERENCES associations (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE associations_causes ADD CONSTRAINT FK_D998BB591D186264 FOREIGN KEY (causes_id) REFERENCES causes (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entreprises_causes ADD CONSTRAINT FK_AF2E9840A70A18EC FOREIGN KEY (entreprises_id) REFERENCES entreprises (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entreprises_causes ADD CONSTRAINT FK_AF2E98401D186264 FOREIGN KEY (causes_id) REFERENCES causes (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE associations_causes DROP FOREIGN KEY FK_D998BB594122538A');
        $this->addSql('ALTER TABLE associations_causes DROP FOREIGN KEY FK_D998BB591D186264');
        $this->addSql('ALTER TABLE entreprises_causes DROP FOREIGN KEY FK_AF2E98401D186264');
        $this->addSql('ALTER TABLE entreprises_causes DROP FOREIGN KEY FK_AF2E9840A70A18EC');
        $this->addSql('DROP TABLE associations');
        $this->addSql('DROP TABLE associations_causes');
        $this->addSql('DROP TABLE causes');
        $this->addSql('DROP TABLE entreprises');
        $this->addSql('DROP TABLE entreprises_causes');
    }
}
