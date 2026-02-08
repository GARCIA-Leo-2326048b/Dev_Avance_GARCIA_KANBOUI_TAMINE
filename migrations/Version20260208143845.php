<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260208143845 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE qcm ADD document_id_id INT DEFAULT NULL, ADD video_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE qcm ADD CONSTRAINT FK_D7A1FEF416E5E825 FOREIGN KEY (document_id_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE qcm ADD CONSTRAINT FK_D7A1FEF4F02697F5 FOREIGN KEY (video_id_id) REFERENCES video (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D7A1FEF416E5E825 ON qcm (document_id_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D7A1FEF4F02697F5 ON qcm (video_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE qcm DROP FOREIGN KEY FK_D7A1FEF416E5E825');
        $this->addSql('ALTER TABLE qcm DROP FOREIGN KEY FK_D7A1FEF4F02697F5');
        $this->addSql('DROP INDEX UNIQ_D7A1FEF416E5E825 ON qcm');
        $this->addSql('DROP INDEX UNIQ_D7A1FEF4F02697F5 ON qcm');
        $this->addSql('ALTER TABLE qcm DROP document_id_id, DROP video_id_id');
    }
}
