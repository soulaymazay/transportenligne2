<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230519145701 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE lesmoyens (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, marque VARCHAR(255) NOT NULL, couleur VARCHAR(255) NOT NULL, annee VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE lesmoyens_chauff (lesmoyens_id INT NOT NULL, chauff_id INT NOT NULL, INDEX IDX_36C0B40529D6E68E (lesmoyens_id), INDEX IDX_36C0B40567F8FFF2 (chauff_id), PRIMARY KEY(lesmoyens_id, chauff_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE lesmoyens_chauff ADD CONSTRAINT FK_36C0B40529D6E68E FOREIGN KEY (lesmoyens_id) REFERENCES lesmoyens (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE lesmoyens_chauff ADD CONSTRAINT FK_36C0B40567F8FFF2 FOREIGN KEY (chauff_id) REFERENCES chauff (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE lesmoyens_chauff DROP FOREIGN KEY FK_36C0B40529D6E68E');
        $this->addSql('ALTER TABLE lesmoyens_chauff DROP FOREIGN KEY FK_36C0B40567F8FFF2');
        $this->addSql('DROP TABLE lesmoyens');
        $this->addSql('DROP TABLE lesmoyens_chauff');
    }
}
