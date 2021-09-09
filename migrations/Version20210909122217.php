<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210909122217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F8A2B5DF02');
        $this->addSql('DROP INDEX IDX_FBD8E0F8A2B5DF02 ON job');
        $this->addSql('ALTER TABLE job CHANGE recruiter_id_id recruiter_id INT NOT NULL');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8156BE243 FOREIGN KEY (recruiter_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_FBD8E0F8156BE243 ON job (recruiter_id)');
        $this->addSql('ALTER TABLE pending_job_request DROP FOREIGN KEY FK_FADCB7C047A475AB');
        $this->addSql('ALTER TABLE pending_job_request DROP FOREIGN KEY FK_FADCB7C07E182327');
        $this->addSql('DROP INDEX IDX_FADCB7C07E182327 ON pending_job_request');
        $this->addSql('DROP INDEX IDX_FADCB7C047A475AB ON pending_job_request');
        $this->addSql('ALTER TABLE pending_job_request ADD job_id INT NOT NULL, ADD candidate_id INT NOT NULL, DROP job_id_id, DROP candidate_id_id');
        $this->addSql('ALTER TABLE pending_job_request ADD CONSTRAINT FK_FADCB7C0BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE pending_job_request ADD CONSTRAINT FK_FADCB7C091BD8781 FOREIGN KEY (candidate_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_FADCB7C0BE04EA9 ON pending_job_request (job_id)');
        $this->addSql('CREATE INDEX IDX_FADCB7C091BD8781 ON pending_job_request (candidate_id)');
        $this->addSql('ALTER TABLE valid_job_request DROP FOREIGN KEY FK_F25CF4E347A475AB');
        $this->addSql('ALTER TABLE valid_job_request DROP FOREIGN KEY FK_F25CF4E37E182327');
        $this->addSql('DROP INDEX IDX_F25CF4E37E182327 ON valid_job_request');
        $this->addSql('DROP INDEX IDX_F25CF4E347A475AB ON valid_job_request');
        $this->addSql('ALTER TABLE valid_job_request ADD job_id INT NOT NULL, ADD candidate_id INT NOT NULL, DROP job_id_id, DROP candidate_id_id');
        $this->addSql('ALTER TABLE valid_job_request ADD CONSTRAINT FK_F25CF4E3BE04EA9 FOREIGN KEY (job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE valid_job_request ADD CONSTRAINT FK_F25CF4E391BD8781 FOREIGN KEY (candidate_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_F25CF4E3BE04EA9 ON valid_job_request (job_id)');
        $this->addSql('CREATE INDEX IDX_F25CF4E391BD8781 ON valid_job_request (candidate_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE job DROP FOREIGN KEY FK_FBD8E0F8156BE243');
        $this->addSql('DROP INDEX IDX_FBD8E0F8156BE243 ON job');
        $this->addSql('ALTER TABLE job CHANGE recruiter_id recruiter_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE job ADD CONSTRAINT FK_FBD8E0F8A2B5DF02 FOREIGN KEY (recruiter_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_FBD8E0F8A2B5DF02 ON job (recruiter_id_id)');
        $this->addSql('ALTER TABLE pending_job_request DROP FOREIGN KEY FK_FADCB7C0BE04EA9');
        $this->addSql('ALTER TABLE pending_job_request DROP FOREIGN KEY FK_FADCB7C091BD8781');
        $this->addSql('DROP INDEX IDX_FADCB7C0BE04EA9 ON pending_job_request');
        $this->addSql('DROP INDEX IDX_FADCB7C091BD8781 ON pending_job_request');
        $this->addSql('ALTER TABLE pending_job_request ADD job_id_id INT NOT NULL, ADD candidate_id_id INT NOT NULL, DROP job_id, DROP candidate_id');
        $this->addSql('ALTER TABLE pending_job_request ADD CONSTRAINT FK_FADCB7C047A475AB FOREIGN KEY (candidate_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE pending_job_request ADD CONSTRAINT FK_FADCB7C07E182327 FOREIGN KEY (job_id_id) REFERENCES job (id)');
        $this->addSql('CREATE INDEX IDX_FADCB7C07E182327 ON pending_job_request (job_id_id)');
        $this->addSql('CREATE INDEX IDX_FADCB7C047A475AB ON pending_job_request (candidate_id_id)');
        $this->addSql('ALTER TABLE valid_job_request DROP FOREIGN KEY FK_F25CF4E3BE04EA9');
        $this->addSql('ALTER TABLE valid_job_request DROP FOREIGN KEY FK_F25CF4E391BD8781');
        $this->addSql('DROP INDEX IDX_F25CF4E3BE04EA9 ON valid_job_request');
        $this->addSql('DROP INDEX IDX_F25CF4E391BD8781 ON valid_job_request');
        $this->addSql('ALTER TABLE valid_job_request ADD job_id_id INT NOT NULL, ADD candidate_id_id INT NOT NULL, DROP job_id, DROP candidate_id');
        $this->addSql('ALTER TABLE valid_job_request ADD CONSTRAINT FK_F25CF4E347A475AB FOREIGN KEY (candidate_id_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE valid_job_request ADD CONSTRAINT FK_F25CF4E37E182327 FOREIGN KEY (job_id_id) REFERENCES job (id)');
        $this->addSql('CREATE INDEX IDX_F25CF4E37E182327 ON valid_job_request (job_id_id)');
        $this->addSql('CREATE INDEX IDX_F25CF4E347A475AB ON valid_job_request (candidate_id_id)');
    }
}
