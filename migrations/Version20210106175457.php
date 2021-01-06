<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210106175457 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_likes DROP FOREIGN KEY FK_E050D68C4B89032C');
        $this->addSql('DROP INDEX IDX_E050D68C4B89032C ON comment_likes');
        $this->addSql('ALTER TABLE comment_likes CHANGE post_id comment_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment_likes ADD CONSTRAINT FK_E050D68CF8697D13 FOREIGN KEY (comment_id) REFERENCES comments (id)');
        $this->addSql('CREATE INDEX IDX_E050D68CF8697D13 ON comment_likes (comment_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE comment_likes DROP FOREIGN KEY FK_E050D68CF8697D13');
        $this->addSql('DROP INDEX IDX_E050D68CF8697D13 ON comment_likes');
        $this->addSql('ALTER TABLE comment_likes CHANGE comment_id post_id INT NOT NULL');
        $this->addSql('ALTER TABLE comment_likes ADD CONSTRAINT FK_E050D68C4B89032C FOREIGN KEY (post_id) REFERENCES posts (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_E050D68C4B89032C ON comment_likes (post_id)');
    }
}
