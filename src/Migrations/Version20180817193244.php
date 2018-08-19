<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20180817193244 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE application_change DROP FOREIGN KEY FK_F064F2E53E030ACD');
        $this->addSql('DROP INDEX IDX_F064F2E53E030ACD ON application_change');
        $this->addSql('ALTER TABLE application_change DROP application_id');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE application_change ADD application_id INT NOT NULL');
        $this->addSql('ALTER TABLE application_change ADD CONSTRAINT FK_F064F2E53E030ACD FOREIGN KEY (application_id) REFERENCES application (id)');
        $this->addSql('CREATE INDEX IDX_F064F2E53E030ACD ON application_change (application_id)');
    }
}
