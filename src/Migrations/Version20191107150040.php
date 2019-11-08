<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191107150040 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE item (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, points INT NOT NULL, description LONGTEXT DEFAULT NULL, type VARCHAR(255) NOT NULL, reach INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_rule (item_id INT NOT NULL, rule_id INT NOT NULL, INDEX IDX_8EE8E837126F525E (item_id), INDEX IDX_8EE8E837744E0351 (rule_id), PRIMARY KEY(item_id, rule_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE item_faction (item_id INT NOT NULL, faction_id INT NOT NULL, INDEX IDX_8859ADC4126F525E (item_id), INDEX IDX_8859ADC44448F8DA (faction_id), PRIMARY KEY(item_id, faction_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE item_rule ADD CONSTRAINT FK_8EE8E837126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_rule ADD CONSTRAINT FK_8EE8E837744E0351 FOREIGN KEY (rule_id) REFERENCES rule (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_faction ADD CONSTRAINT FK_8859ADC4126F525E FOREIGN KEY (item_id) REFERENCES item (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE item_faction ADD CONSTRAINT FK_8859ADC44448F8DA FOREIGN KEY (faction_id) REFERENCES faction (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE item_rule DROP FOREIGN KEY FK_8EE8E837126F525E');
        $this->addSql('ALTER TABLE item_faction DROP FOREIGN KEY FK_8859ADC4126F525E');
        $this->addSql('DROP TABLE item');
        $this->addSql('DROP TABLE item_rule');
        $this->addSql('DROP TABLE item_faction');
    }
}
