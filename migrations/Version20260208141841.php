<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260208141841 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE response DROP FOREIGN KEY `FK_3E7B0BFBFF6241A6`');
        $this->addSql('DROP INDEX IDX_3E7B0BFBFF6241A6 ON response');
        $this->addSql('ALTER TABLE response ADD label VARCHAR(255) NOT NULL, DROP question, DROP response, DROP qcm_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE response ADD response VARCHAR(255) NOT NULL, ADD qcm_id INT NOT NULL, CHANGE label question VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT `FK_3E7B0BFBFF6241A6` FOREIGN KEY (qcm_id) REFERENCES qcm (id)');
        $this->addSql('CREATE INDEX IDX_3E7B0BFBFF6241A6 ON response (qcm_id)');
    }
}
