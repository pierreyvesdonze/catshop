<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210704154318 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery_address ADD user_id INT NOT NULL');
        $this->addSql('ALTER TABLE delivery_address ADD CONSTRAINT FK_750D05FA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_750D05FA76ED395 ON delivery_address (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE delivery_address DROP FOREIGN KEY FK_750D05FA76ED395');
        $this->addSql('DROP INDEX IDX_750D05FA76ED395 ON delivery_address');
        $this->addSql('ALTER TABLE delivery_address DROP user_id');
    }
}
