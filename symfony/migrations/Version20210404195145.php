<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210404195145 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE category (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE dvd_category (dvd_id INT NOT NULL, category_id INT NOT NULL, INDEX IDX_509FE5AD797185F6 (dvd_id), INDEX IDX_509FE5AD12469DE2 (category_id), PRIMARY KEY(dvd_id, category_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE dvd_category ADD CONSTRAINT FK_509FE5AD797185F6 FOREIGN KEY (dvd_id) REFERENCES dvd (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE dvd_category ADD CONSTRAINT FK_509FE5AD12469DE2 FOREIGN KEY (category_id) REFERENCES category (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE dvd_category DROP FOREIGN KEY FK_509FE5AD12469DE2');
        $this->addSql('DROP TABLE category');
        $this->addSql('DROP TABLE dvd_category');
    }
}
