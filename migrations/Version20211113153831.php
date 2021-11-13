<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211113153831 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('
            CREATE TABLE category (
                category_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                wallet_id INT UNSIGNED NOT NULL,
                type VARCHAR(10) NOT NULL,
                name VARCHAR(50) NOT NULL,
                INDEX IDX_64C19C1712520F3 (wallet_id),
                PRIMARY KEY(category_id)
            ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB
        ');
        $this->addSql('
            CREATE TABLE wallet (
                wallet_id INT UNSIGNED AUTO_INCREMENT NOT NULL,
                name VARCHAR(50) NOT NULL,
                currency VARCHAR(3) NOT NULL,
                PRIMARY KEY(wallet_id)
            ) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE category ADD CONSTRAINT FK_64C19C1712520F3 FOREIGN KEY (wallet_id) REFERENCES wallet (wallet_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_7C68921F5E237E06 ON wallet (name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE category DROP FOREIGN KEY FK_64C19C1712520F3');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE wallet');
    }
}
