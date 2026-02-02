<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260202150623 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE qcm (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(30) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE response (id INT AUTO_INCREMENT NOT NULL, question VARCHAR(255) NOT NULL, response VARCHAR(255) NOT NULL, qcm_id INT NOT NULL, INDEX IDX_3E7B0BFBFF6241A6 (qcm_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFBFF6241A6 FOREIGN KEY (qcm_id) REFERENCES qcm (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFBFF6241A6');
        $this->addSql('DROP TABLE qcm');
        $this->addSql('DROP TABLE response');
    }
}
