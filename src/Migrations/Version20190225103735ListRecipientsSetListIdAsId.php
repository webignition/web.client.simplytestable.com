<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190225103735ListRecipientsSetListIdAsId extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ListRecipients MODIFY id INT NOT NULL');
        $this->addSql('ALTER TABLE ListRecipients DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE ListRecipients DROP id, CHANGE listId listId VARCHAR(16) NOT NULL');
        $this->addSql('ALTER TABLE ListRecipients ADD PRIMARY KEY (listId)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE ListRecipients DROP PRIMARY KEY');
        $this->addSql('ALTER TABLE ListRecipients ADD id INT AUTO_INCREMENT NOT NULL, CHANGE listId listId VARCHAR(255) NOT NULL COLLATE utf8_unicode_ci');
        $this->addSql('ALTER TABLE ListRecipients ADD PRIMARY KEY (id)');
    }
}
