<?php declare(strict_types=1);

namespace Application\Migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190312160848DropTestTimePeriod extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Test DROP FOREIGN KEY FK_784DD132E43FFED1');
        $this->addSql('DROP INDEX UNIQ_784DD132E43FFED1 ON Test');
        $this->addSql('ALTER TABLE Test DROP timePeriod_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE Test ADD timePeriod_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE Test ADD CONSTRAINT FK_784DD132E43FFED1 FOREIGN KEY (timePeriod_id) REFERENCES TimePeriod (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_784DD132E43FFED1 ON Test (timePeriod_id)');
    }
}
