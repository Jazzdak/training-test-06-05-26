<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260128170855 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE conference (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              name VARCHAR(255) NOT NULL,
              description CLOB NOT NULL,
              accessible BOOLEAN NOT NULL,
              prerequisites CLOB DEFAULT NULL,
              start_at DATETIME NOT NULL,
              end_at DATETIME NOT NULL
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE organization (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              name VARCHAR(255) NOT NULL,
              presentation CLOB NOT NULL,
              created_at DATETIME NOT NULL
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE organization_conference (
              organization_id INTEGER NOT NULL,
              conference_id INTEGER NOT NULL,
              PRIMARY KEY (organization_id, conference_id),
              CONSTRAINT FK_784EA12732C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE,
              CONSTRAINT FK_784EA127604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_784EA12732C8A3DE ON organization_conference (organization_id)');
        $this->addSql('CREATE INDEX IDX_784EA127604B8382 ON organization_conference (conference_id)');
        $this->addSql(<<<'SQL'
            CREATE TABLE user (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              email VARCHAR(180) NOT NULL,
              roles CLOB NOT NULL,
              password VARCHAR(255) NOT NULL
            )
        SQL);
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_EMAIL ON user (email)');
        $this->addSql(<<<'SQL'
            CREATE TABLE user_organization (
              user_id INTEGER NOT NULL,
              organization_id INTEGER NOT NULL,
              PRIMARY KEY (user_id, organization_id),
              CONSTRAINT FK_41221F7EA76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE,
              CONSTRAINT FK_41221F7E32C8A3DE FOREIGN KEY (organization_id) REFERENCES organization (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_41221F7EA76ED395 ON user_organization (user_id)');
        $this->addSql('CREATE INDEX IDX_41221F7E32C8A3DE ON user_organization (organization_id)');
        $this->addSql(<<<'SQL'
            CREATE TABLE volunteering (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              start_at DATETIME NOT NULL,
              end_at DATETIME NOT NULL,
              conference_id INTEGER NOT NULL,
              for_user_id INTEGER NOT NULL,
              CONSTRAINT FK_7854E8EE604B8382 FOREIGN KEY (conference_id) REFERENCES conference (id) NOT DEFERRABLE INITIALLY IMMEDIATE,
              CONSTRAINT FK_7854E8EE9B5BB4B8 FOREIGN KEY (for_user_id) REFERENCES user (id) NOT DEFERRABLE INITIALLY IMMEDIATE
            )
        SQL);
        $this->addSql('CREATE INDEX IDX_7854E8EE604B8382 ON volunteering (conference_id)');
        $this->addSql('CREATE INDEX IDX_7854E8EE9B5BB4B8 ON volunteering (for_user_id)');
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (
              id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
              body CLOB NOT NULL,
              headers CLOB NOT NULL,
              queue_name VARCHAR(190) NOT NULL,
              created_at DATETIME NOT NULL,
              available_at DATETIME NOT NULL,
              delivered_at DATETIME DEFAULT NULL
            )
        SQL);
        $this->addSql(<<<'SQL'
            CREATE INDEX IDX_75EA56E0FB7336F0E3BD61CE16BA31DBBF396750 ON messenger_messages (
              queue_name, available_at, delivered_at,
              id
            )
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE conference');
        $this->addSql('DROP TABLE organization');
        $this->addSql('DROP TABLE organization_conference');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_organization');
        $this->addSql('DROP TABLE volunteering');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
