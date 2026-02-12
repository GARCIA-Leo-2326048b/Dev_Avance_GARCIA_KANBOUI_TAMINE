<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260212184106 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document DROP FOREIGN KEY `FK_D8698A76FF6241A6`');
        $this->addSql('DROP INDEX UNIQ_D8698A76FF6241A6 ON document');
        $this->addSql('ALTER TABLE document DROP qcm_id');
        $this->addSql('ALTER TABLE qcm DROP FOREIGN KEY `FK_D7A1FEF429C1004E`');
        $this->addSql('ALTER TABLE qcm DROP FOREIGN KEY `FK_D7A1FEF4C33F7837`');
        $this->addSql('ALTER TABLE qcm CHANGE title title VARCHAR(100) NOT NULL');
        $this->addSql('ALTER TABLE qcm ADD CONSTRAINT FK_D7A1FEF429C1004E FOREIGN KEY (video_id) REFERENCES video (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE qcm ADD CONSTRAINT FK_D7A1FEF4C33F7837 FOREIGN KEY (document_id) REFERENCES document (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE video DROP FOREIGN KEY `FK_7CC7DA2CFF6241A6`');
        $this->addSql('DROP INDEX UNIQ_7CC7DA2CFF6241A6 ON video');
        $this->addSql('ALTER TABLE video DROP qcm_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE document ADD qcm_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE document ADD CONSTRAINT `FK_D8698A76FF6241A6` FOREIGN KEY (qcm_id) REFERENCES qcm (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D8698A76FF6241A6 ON document (qcm_id)');
        $this->addSql('ALTER TABLE qcm DROP FOREIGN KEY FK_D7A1FEF4C33F7837');
        $this->addSql('ALTER TABLE qcm DROP FOREIGN KEY FK_D7A1FEF429C1004E');
        $this->addSql('ALTER TABLE qcm CHANGE title title VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE qcm ADD CONSTRAINT `FK_D7A1FEF4C33F7837` FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE qcm ADD CONSTRAINT `FK_D7A1FEF429C1004E` FOREIGN KEY (video_id) REFERENCES video (id)');
        $this->addSql('ALTER TABLE video ADD qcm_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE video ADD CONSTRAINT `FK_7CC7DA2CFF6241A6` FOREIGN KEY (qcm_id) REFERENCES qcm (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7CC7DA2CFF6241A6 ON video (qcm_id)');
    }
}
