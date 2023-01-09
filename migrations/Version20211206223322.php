<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20211206223322 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE filable_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE file_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "users_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE comment (id INT NOT NULL, author_id INT NOT NULL, post_id INT NOT NULL, body TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9474526CF675F31B ON comment (author_id)');
        $this->addSql('CREATE INDEX IDX_9474526C4B89032C ON comment (post_id)');
        $this->addSql('COMMENT ON COLUMN comment.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE filable (id INT NOT NULL, dtype VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE file (id INT NOT NULL, filable_id INT DEFAULT NULL, path VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8C9F3610E930F615 ON file (filable_id)');
        $this->addSql('CREATE TABLE post (id INT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, body TEXT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5A8A6C8DF675F31B ON post (author_id)');
        $this->addSql('COMMENT ON COLUMN post.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN post.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE talk (id INT NOT NULL, sender_id INT NOT NULL, receiver_id INT NOT NULL, text_message TEXT NOT NULL, send_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_9F24D5BBF624B39D ON talk (sender_id)');
        $this->addSql('CREATE INDEX IDX_9F24D5BBCD53EDB6 ON talk (receiver_id)');
        $this->addSql('CREATE TABLE "users" (id INT NOT NULL, email VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, birth_date DATE DEFAULT NULL, first_name VARCHAR(30) NOT NULL, last_name VARCHAR(30) NOT NULL, profile_pic VARCHAR(50) DEFAULT NULL, joined_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, gender VARCHAR(10) DEFAULT NULL, type_user VARCHAR(255) NOT NULL, speciality VARCHAR(30) DEFAULT NULL, profile_overview TEXT DEFAULT NULL, verified BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1483A5E9E7927C74 ON "users" (email)');
        $this->addSql('COMMENT ON COLUMN "users".joined_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CF675F31B FOREIGN KEY (author_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C4B89032C FOREIGN KEY (post_id) REFERENCES post (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CBF396750 FOREIGN KEY (id) REFERENCES filable (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE file ADD CONSTRAINT FK_8C9F3610E930F615 FOREIGN KEY (filable_id) REFERENCES filable (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DF675F31B FOREIGN KEY (author_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE post ADD CONSTRAINT FK_5A8A6C8DBF396750 FOREIGN KEY (id) REFERENCES filable (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE talk ADD CONSTRAINT FK_9F24D5BBF624B39D FOREIGN KEY (sender_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE talk ADD CONSTRAINT FK_9F24D5BBCD53EDB6 FOREIGN KEY (receiver_id) REFERENCES "users" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE talk ADD CONSTRAINT FK_9F24D5BBBF396750 FOREIGN KEY (id) REFERENCES filable (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CBF396750');
        $this->addSql('ALTER TABLE file DROP CONSTRAINT FK_8C9F3610E930F615');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DBF396750');
        $this->addSql('ALTER TABLE talk DROP CONSTRAINT FK_9F24D5BBBF396750');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526C4B89032C');
        $this->addSql('ALTER TABLE comment DROP CONSTRAINT FK_9474526CF675F31B');
        $this->addSql('ALTER TABLE post DROP CONSTRAINT FK_5A8A6C8DF675F31B');
        $this->addSql('ALTER TABLE talk DROP CONSTRAINT FK_9F24D5BBF624B39D');
        $this->addSql('ALTER TABLE talk DROP CONSTRAINT FK_9F24D5BBCD53EDB6');
        $this->addSql('DROP SEQUENCE filable_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE file_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE "users_id_seq" CASCADE');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE filable');
        $this->addSql('DROP TABLE file');
        $this->addSql('DROP TABLE post');
        $this->addSql('DROP TABLE talk');
        $this->addSql('DROP TABLE "users"');
    }
}
