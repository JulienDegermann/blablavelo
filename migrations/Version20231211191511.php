<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231211191511 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0C645C84A');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0C645C84A FOREIGN KEY (user_creator_id) REFERENCES `user` (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0C645C84A');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0C645C84A FOREIGN KEY (user_creator_id) REFERENCES user (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
    }
}
