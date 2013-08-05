<?php

namespace Application\Migrations;

use SimplyTestable\BaseMigrationsBundle\Migration\BaseMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120824212120_create_TaskOutput extends BaseMigration
{
    public function up(Schema $schema)
    {  
        
/**
        $this->addSql("ALTER TABLE TaskOutput ADD task_id INT DEFAULT NULL");
        $this->addSql("ALTER TABLE TaskOutput ADD CONSTRAINT FK_C9B3E5C48DB60186 FOREIGN KEY (task_id) REFERENCES Task (id)");
        $this->addSql("CREATE UNIQUE INDEX UNIQ_C9B3E5C48DB60186 ON TaskOutput (task_id)"); 
 */        
        
        $this->statements['mysql'] = array(
            "CREATE TABLE TaskOutput (
                id INT AUTO_INCREMENT NOT NULL,
                content LONGTEXT DEFAULT NULL,
                type VARCHAR(255) NOT NULL,
                task_id INT NOT NULL,
                PRIMARY KEY(id),
                UNIQUE INDEX UNIQ_C9B3E5C48DB60186 (task_id))
                DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci ENGINE = InnoDB",
            "ALTER TABLE TaskOutput ADD CONSTRAINT FK_C9B3E5C48DB60186 FOREIGN KEY (task_id) REFERENCES Task (id)"
        );
        
        $this->statements['sqlite'] = array(
            "CREATE TABLE TaskOutput (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                content LONGTEXT DEFAULT NULL COLLATE NOCASE,
                type VARCHAR(255) NOT NULL COLLATE NOCASE
             )"
        );
        
        parent::up($schema);
    }

    public function down(Schema $schema)
    {
        $this->addCommonStatement("DROP TABLE TaskOutput");        
        parent::down($schema);
    }
}
