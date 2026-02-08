<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260208143447 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE response ADD question_id_id INT NOT NULL');
        $this->addSql('ALTER TABLE response ADD CONSTRAINT FK_3E7B0BFB4FAF8F53 FOREIGN KEY (question_id_id) REFERENCES question (id)');
        $this->addSql('CREATE INDEX IDX_3E7B0BFB4FAF8F53 ON response (question_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE response DROP FOREIGN KEY FK_3E7B0BFB4FAF8F53');
        $this->addSql('DROP INDEX IDX_3E7B0BFB4FAF8F53 ON response');
        $this->addSql('ALTER TABLE response DROP question_id_id');
    }
}
