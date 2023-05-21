<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230519145256 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chauff ADD relation_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE chauff ADD CONSTRAINT FK_D036D24A3256915B FOREIGN KEY (relation_id) REFERENCES clients (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D036D24A3256915B ON chauff (relation_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE chauff DROP FOREIGN KEY FK_D036D24A3256915B');
        $this->addSql('DROP INDEX UNIQ_D036D24A3256915B ON chauff');
        $this->addSql('ALTER TABLE chauff DROP relation_id');
    }
}
