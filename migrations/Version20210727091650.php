<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210727091650 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE movie (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, title VARCHAR(255) NOT NULL, duration INTEGER NOT NULL, release_date DATE NOT NULL, plot CLOB NOT NULL, image VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE review (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, movie_id INTEGER NOT NULL, rating SMALLINT NOT NULL, content CLOB DEFAULT NULL)');
        $this->addSql('CREATE INDEX IDX_794381C6A76ED395 ON review (user_id)');
        $this->addSql('CREATE INDEX IDX_794381C68F93B6FC ON review (movie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE movie');
        $this->addSql('DROP TABLE review');
    }
}
