<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250217132025 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE figurine ADD oeuvre_id INT DEFAULT NULL, DROP category_id');
        $this->addSql('ALTER TABLE figurine ADD CONSTRAINT FK_9DD647888194DE8 FOREIGN KEY (oeuvre_id) REFERENCES oeuvre (id)');
        $this->addSql('CREATE INDEX IDX_9DD647888194DE8 ON figurine (oeuvre_id)');
        $this->addSql('ALTER TABLE oeuvre ADD category_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE oeuvre ADD CONSTRAINT FK_35FE2EFE12469DE2 FOREIGN KEY (category_id) REFERENCES category (id)');
        $this->addSql('CREATE INDEX IDX_35FE2EFE12469DE2 ON oeuvre (category_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE oeuvre DROP FOREIGN KEY FK_35FE2EFE12469DE2');
        $this->addSql('DROP INDEX IDX_35FE2EFE12469DE2 ON oeuvre');
        $this->addSql('ALTER TABLE oeuvre DROP category_id');
        $this->addSql('ALTER TABLE figurine DROP FOREIGN KEY FK_9DD647888194DE8');
        $this->addSql('DROP INDEX IDX_9DD647888194DE8 ON figurine');
        $this->addSql('ALTER TABLE figurine ADD category_id INT NOT NULL, DROP oeuvre_id');
    }
}
