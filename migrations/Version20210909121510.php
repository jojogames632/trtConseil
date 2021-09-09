<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210909121510 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, recruiter_id_id INT NOT NULL, title VARCHAR(255) NOT NULL, place VARCHAR(255) NOT NULL, schedule_start TIME NOT NULL, schedule_end TIME NOT NULL, salary INT NOT NULL, year_experience_required INT NOT NULL, description LONGTEXT NOT NULL, is_valid TINYINT(1) NOT NULL, INDEX IDX_FBD8E0F8A2B5DF02 (recruiter_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pending_job_request (id INT AUTO_INCREMENT NOT NULL, job_id_id INT NOT NULL, candidate_id_id INT NOT NULL, INDEX IDX_FADCB7C07E182327 (job_id_id), INDEX IDX_FADCB7C047A475AB (candidate_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, roles LONGTEXT NOT NULL COMMENT \'(DC2Type:json)\', password VARCHAR(255) NOT NULL, is_active TINYINT(1) NOT NULL, company VARCHAR(255) DEFAULT NULL, address VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, cv_filename VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE valid_job_request (id INT AUTO_INCREMENT NOT NULL, job_id_id INT NOT NULL, candidate_id_id INT NOT NULL, INDEX IDX_F25CF4E37E182327 (job_id_id), INDEX IDX_F25CF4E347A475AB (candidate_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8A2B5DF02 FOREIGN KEY (recruiter_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE pending_job_request ADD CONSTRAINT FK_FADCB7C07E182327 FOREIGN KEY (job_id_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE pending_job_request ADD CONSTRAINT FK_FADCB7C047A475AB FOREIGN KEY (candidate_id_id) REFERENCES `user` (id)');
        $this->addSql('ALTER TABLE valid_job_request ADD CONSTRAINT FK_F25CF4E37E182327 FOREIGN KEY (job_id_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE valid_job_request ADD CONSTRAINT FK_F25CF4E347A475AB FOREIGN KEY (candidate_id_id) REFERENCES `user` (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pending_job_request DROP FOREIGN KEY FK_FADCB7C07E182327');
        $this->addSql('ALTER TABLE valid_job_request DROP FOREIGN KEY FK_F25CF4E37E182327');
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F8A2B5DF02');
        $this->addSql('ALTER TABLE pending_job_request DROP FOREIGN KEY FK_FADCB7C047A475AB');
        $this->addSql('ALTER TABLE valid_job_request DROP FOREIGN KEY FK_F25CF4E347A475AB');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE pending_job_request');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE valid_job_request');
    }
}
