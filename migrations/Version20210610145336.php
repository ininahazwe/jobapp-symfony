<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210610145336 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre ADD modele_offre_commerciale_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE offre ADD CONSTRAINT FK_AF86866FB301AC FOREIGN KEY (modele_offre_commerciale_id) REFERENCES modele_offre_commerciale (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_AF86866FB301AC ON offre (modele_offre_commerciale_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE offre DROP FOREIGN KEY FK_AF86866FB301AC');
        $this->addSql('DROP INDEX UNIQ_AF86866FB301AC ON offre');
        $this->addSql('ALTER TABLE offre DROP modele_offre_commerciale_id');
    }
}
