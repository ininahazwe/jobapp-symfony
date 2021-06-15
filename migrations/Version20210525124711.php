<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210525124711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entreprise_recruteur (entreprise_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_B653C025A4AEAFEA (entreprise_id), INDEX IDX_B653C025A76ED395 (user_id), PRIMARY KEY(entreprise_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE entreprise_super_recruteur (entreprise_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_6336D26BA4AEAFEA (entreprise_id), INDEX IDX_6336D26BA76ED395 (user_id), PRIMARY KEY(entreprise_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE entreprise_recruteur ADD CONSTRAINT FK_B653C025A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE entreprise_recruteur ADD CONSTRAINT FK_B653C025A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE entreprise_super_recruteur ADD CONSTRAINT FK_6336D26BA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE entreprise_super_recruteur ADD CONSTRAINT FK_6336D26BA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE entreprise_super_recruteurs');
        $this->addSql('DROP TABLE entreprise_user');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE entreprise_super_recruteurs (entreprise_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C10D4109A76ED395 (user_id), INDEX IDX_C10D4109A4AEAFEA (entreprise_id), PRIMARY KEY(entreprise_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE entreprise_user (entreprise_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_606C16EA76ED395 (user_id), INDEX IDX_606C16EA4AEAFEA (entreprise_id), PRIMARY KEY(entreprise_id, user_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE entreprise_super_recruteurs ADD CONSTRAINT FK_C10D4109A4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id)');
        $this->addSql('ALTER TABLE entreprise_super_recruteurs ADD CONSTRAINT FK_C10D4109A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE entreprise_user ADD CONSTRAINT FK_606C16EA4AEAFEA FOREIGN KEY (entreprise_id) REFERENCES entreprise (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE entreprise_user ADD CONSTRAINT FK_606C16EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE entreprise_recruteur');
        $this->addSql('DROP TABLE entreprise_super_recruteur');
    }
}
