<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210702180137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE cart_line');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart_line (id INT AUTO_INCREMENT NOT NULL, cart_id_id INT NOT NULL, article_id_id INT NOT NULL, INDEX IDX_3EF1B4CF20AEF35F (cart_id_id), UNIQUE INDEX UNIQ_3EF1B4CF8F3EC46 (article_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE cart_line ADD CONSTRAINT FK_3EF1B4CF20AEF35F FOREIGN KEY (cart_id_id) REFERENCES cart (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('ALTER TABLE cart_line ADD CONSTRAINT FK_3EF1B4CF8F3EC46 FOREIGN KEY (article_id_id) REFERENCES article (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
