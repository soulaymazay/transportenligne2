<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230530165925 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesmoyens_chauff DROP FOREIGN KEY FK_36C0B40529D6E68E');
        $this->addSql('ALTER TABLE lesmoyens_chauff DROP FOREIGN KEY FK_36C0B40567F8FFF2');
        $this->addSql('DROP TABLE lesmoyens_chauff');
        $this->addSql('ALTER TABLE lesmoyens ADD chauff_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE lesmoyens ADD CONSTRAINT FK_DC847E1D67F8FFF2 FOREIGN KEY (chauff_id) REFERENCES chauff (id)');
        $this->addSql('CREATE INDEX IDX_DC847E1D67F8FFF2 ON lesmoyens (chauff_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesmoyens_chauff (lesmoyens_id INT NOT NULL, chauff_id INT NOT NULL, INDEX IDX_36C0B40567F8FFF2 (chauff_id), INDEX IDX_36C0B40529D6E68E (lesmoyens_id), PRIMARY KEY(lesmoyens_id, chauff_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE lesmoyens_chauff ADD CONSTRAINT FK_36C0B40529D6E68E FOREIGN KEY (lesmoyens_id) REFERENCES lesmoyens (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesmoyens_chauff ADD CONSTRAINT FK_36C0B40567F8FFF2 FOREIGN KEY (chauff_id) REFERENCES chauff (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesmoyens DROP FOREIGN KEY FK_DC847E1D67F8FFF2');
        $this->addSql('DROP INDEX IDX_DC847E1D67F8FFF2 ON lesmoyens');
        $this->addSql('ALTER TABLE lesmoyens DROP chauff_id');
    }
}
