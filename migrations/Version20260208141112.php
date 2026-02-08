<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260208141112 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE question (id INT AUTO_INCREMENT NOT NULL, question VARCHAR(255) NOT NULL, qcm_id INT NOT NULL, INDEX IDX_B6F7494EFF6241A6 (qcm_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE question ADD CONSTRAINT FK_B6F7494EFF6241A6 FOREIGN KEY (qcm_id) REFERENCES qcm (id)');
        $this->addSql('DROP TABLE course');
        $this->addSql('ALTER TABLE document ADD title VARCHAR(255) NOT NULL, ADD description VARCHAR(255) DEFAULT NULL, ADD pages INT NOT NULL, ADD teacher_id INT NOT NULL, ADD qcm_id INT NOT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A7641807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT FK_D8698A76FF6241A6 FOREIGN KEY (qcm_id) REFERENCES qcm (id)');
        $this->addSql('CREATE INDEX IDX_D8698A7641807E1D ON document (teacher_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8698A76FF6241A6 ON document (qcm_id)');
        $this->addSql('ALTER TABLE user ADD name VARCHAR(255) NOT NULL, ADD surname VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE video ADD title VARCHAR(255) NOT NULL, ADD description VARCHAR(255) DEFAULT NULL, ADD teacher_id INT NOT NULL, ADD qcm_id INT NOT NULL');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2C41807E1D FOREIGN KEY (teacher_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT FK_7CC7DA2CFF6241A6 FOREIGN KEY (qcm_id) REFERENCES qcm (id)');
        $this->addSql('CREATE INDEX IDX_7CC7DA2C41807E1D ON video (teacher_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7CC7DA2CFF6241A6 ON video (qcm_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, teacher VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_general_ci`, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_general_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE question DROP FOREIGN KEY FK_B6F7494EFF6241A6');
        $this->addSql('DROP TABLE question');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A7641807E1D');
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY FK_D8698A76FF6241A6');
        $this->addSql('DROP INDEX IDX_D8698A7641807E1D ON document');
        $this->addSql('DROP INDEX UNIQ_D8698A76FF6241A6 ON document');
        $this->addSql('ALTER TABLE document DROP title, DROP description, DROP pages, DROP teacher_id, DROP qcm_id');
        $this->addSql('ALTER TABLE user DROP name, DROP surname');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2C41807E1D');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY FK_7CC7DA2CFF6241A6');
        $this->addSql('DROP INDEX IDX_7CC7DA2C41807E1D ON video');
        $this->addSql('DROP INDEX UNIQ_7CC7DA2CFF6241A6 ON video');
        $this->addSql('ALTER TABLE video DROP title, DROP description, DROP teacher_id, DROP qcm_id');
    }
}
