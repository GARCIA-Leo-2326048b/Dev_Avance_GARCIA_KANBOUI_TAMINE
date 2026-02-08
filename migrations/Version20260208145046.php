<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260208145046 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE quizz_attempt (id INT AUTO_INCREMENT NOT NULL, grade DOUBLE PRECISION NOT NULL, student_id INT NOT NULL, qcm_id INT NOT NULL, INDEX IDX_7206DDB2CB944F1A (student_id), INDEX IDX_7206DDB2FF6241A6 (qcm_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE quizz_attempt ADD CONSTRAINT FK_7206DDB2CB944F1A FOREIGN KEY (student_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quizz_attempt ADD CONSTRAINT FK_7206DDB2FF6241A6 FOREIGN KEY (qcm_id) REFERENCES qcm (id)');
        $this->addSql('ALTER TABLE response ADD is_correct TINYINT NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE quizz_attempt DROP FOREIGN KEY FK_7206DDB2CB944F1A');
        $this->addSql('ALTER TABLE quizz_attempt DROP FOREIGN KEY FK_7206DDB2FF6241A6');
        $this->addSql('DROP TABLE quizz_attempt');
        $this->addSql('ALTER TABLE response DROP is_correct');
    }
}
