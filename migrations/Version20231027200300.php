<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231027200300 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user ADD practice_id INT NOT NULL, ADD bike_id INT NOT NULL');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649ED33821 FOREIGN KEY (practice_id) REFERENCES practice (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D5A4816F FOREIGN KEY (bike_id) REFERENCES model (id)');
        $this->addSql('CREATE INDEX IDX_8D93D649ED33821 ON user (practice_id)');
        $this->addSql('CREATE INDEX IDX_8D93D649D5A4816F ON user (bike_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649ED33821');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649D5A4816F');
        $this->addSql('DROP INDEX IDX_8D93D649ED33821 ON `user`');
        $this->addSql('DROP INDEX IDX_8D93D649D5A4816F ON `user`');
        $this->addSql('ALTER TABLE `user` DROP practice_id, DROP bike_id');
    }
}
