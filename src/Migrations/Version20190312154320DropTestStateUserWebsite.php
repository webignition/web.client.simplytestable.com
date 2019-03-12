<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190312154320DropTestStateUserWebsite extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX state_idx ON Test');
        $this->addSql('DROP INDEX website_idx ON Test');
        $this->addSql('DROP INDEX user_idx ON Test');
        $this->addSql('ALTER TABLE Test DROP user, DROP website, DROP state');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Test ADD user VARCHAR(255) NOT NULL COLLATE utf8_general_ci, ADD website LONGTEXT NOT NULL COLLATE utf8_general_ci, ADD state VARCHAR(255) NOT NULL COLLATE utf8_general_ci');
        $this->addSql('CREATE INDEX state_idx ON Test (state)');
        $this->addSql('CREATE INDEX website_idx ON Test (website(255))');
        $this->addSql('CREATE INDEX user_idx ON Test (user)');
    }
}
