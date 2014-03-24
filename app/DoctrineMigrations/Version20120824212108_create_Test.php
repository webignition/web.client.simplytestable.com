<?php

namespace Application\Migrations;

use SimplyTestable\BaseMigrationsBundle\Migration\BaseMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120824212108_create_Test extends BaseMigration
{
    public function up(Schema $schema)
    {
        $this->statements['mysql'] = array(
            "CREATE TABLE Test (
                id INT AUTO_INCREMENT NOT NULL,
                user VARCHAR(255) NOT NULL, 
                website VARCHAR(255) NOT NULL,
                state VARCHAR(255) NOT NULL,
                taskTypes LONGTEXT NOT NULL COMMENT '(DC2Type:array)',
                timePeriod_id INT DEFAULT NULL,
                testId INT NOT NULL,
                UNIQUE INDEX UNIQ_784DD132E43FFED1 (timePeriod_id),
                INDEX user_idx (user),
                INDEX website_idx (website),
                INDEX state_idx (state),
                INDEX testId_idx (testId),
                PRIMARY KEY(id))
                DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB",
            "ALTER TABLE Test ADD CONSTRAINT FK_784DD132E43FFED1 FOREIGN KEY (timePeriod_id) REFERENCES TimePeriod (id)"
        );
        
        $this->statements['sqlite'] = array(            
            "CREATE TABLE Test (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                user VARCHAR(255) NOT NULL COLLATE NOCASE,
                website LONGTEXT NOT NULL COLLATE NOCASE,
                state VARCHAR(255) NOT NULL COLLATE NOCASE,
                taskTypes LONGTEXT NOT NULL COLLATE NOCASE,
                timePeriod_id INT DEFAULT NULL,
                testId INT NOT NULL,
                FOREIGN KEY (timePeriod_id) REFERENCES TimePeriod (id))",
            "CREATE UNIQUE INDEX UNIQ_784DD132E43FFED1 ON Test (timePeriod_id)",
            "CREATE INDEX user_idx ON Test (user)",
            "CREATE INDEX website_idx ON Test (website)",
            "CREATE INDEX state_idx ON Test (state)",
            "CREATE INDEX testId_idx ON Test (testId)"
        );
        
        parent::up($schema);
    }

    public function down(Schema $schema)
    {
        $this->addCommonStatement("DROP TABLE Test");        
        parent::down($schema);
    }
}
