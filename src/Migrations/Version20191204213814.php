<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191204213814 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE medecin (id INT AUTO_INCREMENT NOT NULL, service_id INT DEFAULT NULL, matricule INT NOT NULL, prenom VARCHAR(45) NOT NULL, nom VARCHAR(45) NOT NULL, email VARCHAR(255) NOT NULL, telephone INT NOT NULL, INDEX IDX_1BDA53C6ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialite (id INT AUTO_INCREMENT NOT NULL, service_id INT DEFAULT NULL, label VARCHAR(255) NOT NULL, INDEX IDX_E7D6FCC1ED5CA9E6 (service_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE specialite_medecin (specialite_id INT NOT NULL, medecin_id INT NOT NULL, INDEX IDX_24D341422195E0F0 (specialite_id), INDEX IDX_24D341424F31A84 (medecin_id), PRIMARY KEY(specialite_id, medecin_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE service (id INT AUTO_INCREMENT NOT NULL, label VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE medecin ADD CONSTRAINT FK_1BDA53C6ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE specialite ADD CONSTRAINT FK_E7D6FCC1ED5CA9E6 FOREIGN KEY (service_id) REFERENCES service (id)');
        $this->addSql('ALTER TABLE specialite_medecin ADD CONSTRAINT FK_24D341422195E0F0 FOREIGN KEY (specialite_id) REFERENCES specialite (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE specialite_medecin ADD CONSTRAINT FK_24D341424F31A84 FOREIGN KEY (medecin_id) REFERENCES medecin (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE specialite_medecin DROP FOREIGN KEY FK_24D341424F31A84');
        $this->addSql('ALTER TABLE specialite_medecin DROP FOREIGN KEY FK_24D341422195E0F0');
        $this->addSql('ALTER TABLE medecin DROP FOREIGN KEY FK_1BDA53C6ED5CA9E6');
        $this->addSql('ALTER TABLE specialite DROP FOREIGN KEY FK_E7D6FCC1ED5CA9E6');
        $this->addSql('DROP TABLE medecin');
        $this->addSql('DROP TABLE specialite');
        $this->addSql('DROP TABLE specialite_medecin');
        $this->addSql('DROP TABLE service');
    }
}
