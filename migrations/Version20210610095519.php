<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210610095519 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu ADD child_menu_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE menu ADD CONSTRAINT FK_7D053A93AE63A109 FOREIGN KEY (child_menu_id) REFERENCES menu (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7D053A93AE63A109 ON menu (child_menu_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE menu DROP FOREIGN KEY FK_7D053A93AE63A109');
        $this->addSql('DROP INDEX UNIQ_7D053A93AE63A109 ON menu');
        $this->addSql('ALTER TABLE menu DROP child_menu_id');
    }
}
