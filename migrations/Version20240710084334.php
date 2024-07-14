<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240710084334 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE assoc_product_order (id INT AUTO_INCREMENT NOT NULL, commande_id INT NOT NULL, product_id INT NOT NULL, quantity INT NOT NULL, INDEX IDX_BCD01BDC82EA2E54 (commande_id), INDEX IDX_BCD01BDC4584665A (product_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE assoc_product_order ADD CONSTRAINT FK_BCD01BDC82EA2E54 FOREIGN KEY (commande_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE assoc_product_order ADD CONSTRAINT FK_BCD01BDC4584665A FOREIGN KEY (product_id) REFERENCES product (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE assoc_product_order DROP FOREIGN KEY FK_BCD01BDC82EA2E54');
        $this->addSql('ALTER TABLE assoc_product_order DROP FOREIGN KEY FK_BCD01BDC4584665A');
        $this->addSql('DROP TABLE assoc_product_order');
    }
}
