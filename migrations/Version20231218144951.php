<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20231218144951 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, department_id INT NOT NULL, name VARCHAR(255) NOT NULL, zip_code INT NOT NULL, insee_code VARCHAR(10) NOT NULL, INDEX IDX_2D5B0234AE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, code VARCHAR(3) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, content LONGTEXT NOT NULL, email VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE mind (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, brand_id INT NOT NULL, name VARCHAR(255) NOT NULL, year DATE NOT NULL COMMENT \'(DC2Type:date_immutable)\', INDEX IDX_D79572D944F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE practice (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ride (id INT AUTO_INCREMENT NOT NULL, mind_id INT NOT NULL, practice_id INT NOT NULL, user_creator_id INT NOT NULL, city_id INT NOT NULL, title VARCHAR(255) NOT NULL, distance INT NOT NULL, ascent INT NOT NULL, max_rider INT NOT NULL, average_speed INT NOT NULL, date DATETIME NOT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL, INDEX IDX_9B3D7CD053B01993 (mind_id), INDEX IDX_9B3D7CD0ED33821 (practice_id), INDEX IDX_9B3D7CD0C645C84A (user_creator_id), INDEX IDX_9B3D7CD08BAC62AF (city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE ride_user (ride_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C6ACE33D302A8A70 (ride_id), INDEX IDX_C6ACE33DA76ED395 (user_id), PRIMARY KEY(ride_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, city_id INT DEFAULT NULL, mind_id INT DEFAULT NULL, practice_id INT DEFAULT NULL, bike_id INT DEFAULT NULL, department_id INT DEFAULT NULL, user_name VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, birth_date DATE DEFAULT NULL COMMENT \'(DC2Type:date_immutable)\', email VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', updated_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', file_name VARCHAR(255) DEFAULT NULL, token VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_8D93D64924A232CF (user_name), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D6498BAC62AF (city_id), INDEX IDX_8D93D64953B01993 (mind_id), INDEX IDX_8D93D649ED33821 (practice_id), INDEX IDX_8D93D649D5A4816F (bike_id), INDEX IDX_8D93D649AE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', available_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', delivered_at DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE city ADD CONSTRAINT FK_2D5B0234AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
        $this->addSql('ALTER TABLE model ADD CONSTRAINT FK_D79572D944F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD053B01993 FOREIGN KEY (mind_id) REFERENCES mind (id)');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0ED33821 FOREIGN KEY (practice_id) REFERENCES practice (id)');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0C645C84A FOREIGN KEY (user_creator_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD08BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE ride_user ADD CONSTRAINT FK_C6ACE33D302A8A70 FOREIGN KEY (ride_id) REFERENCES ride (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE ride_user ADD CONSTRAINT FK_C6ACE33DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D6498BAC62AF FOREIGN KEY (city_id) REFERENCES city (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D64953B01993 FOREIGN KEY (mind_id) REFERENCES mind (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649ED33821 FOREIGN KEY (practice_id) REFERENCES practice (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649D5A4816F FOREIGN KEY (bike_id) REFERENCES model (id)');
        $this->addSql('ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234AE80F5DF');
        $this->addSql('ALTER TABLE model DROP FOREIGN KEY FK_D79572D944F5D008');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD053B01993');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0ED33821');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0C645C84A');
        $this->addSql('ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD08BAC62AF');
        $this->addSql('ALTER TABLE ride_user DROP FOREIGN KEY FK_C6ACE33D302A8A70');
        $this->addSql('ALTER TABLE ride_user DROP FOREIGN KEY FK_C6ACE33DA76ED395');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D6498BAC62AF');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64953B01993');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649ED33821');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649D5A4816F');
        $this->addSql('ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649AE80F5DF');
        $this->addSql('DROP TABLE brand');
        $this->addSql('DROP TABLE city');
        $this->addSql('DROP TABLE department');
        $this->addSql('DROP TABLE message');
        $this->addSql('DROP TABLE mind');
        $this->addSql('DROP TABLE model');
        $this->addSql('DROP TABLE practice');
        $this->addSql('DROP TABLE ride');
        $this->addSql('DROP TABLE ride_user');
        $this->addSql('DROP TABLE `user`');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
