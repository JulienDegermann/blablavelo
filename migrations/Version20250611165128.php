<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250611165128 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE brand (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', name_number VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE city (id INT AUTO_INCREMENT NOT NULL, department_id INT NOT NULL, zip_code VARCHAR(255) NOT NULL, insee_code VARCHAR(10) NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_2D5B0234AE80F5DF (department_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE department (id INT AUTO_INCREMENT NOT NULL, code VARCHAR(3) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE message (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, title VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', text LONGTEXT NOT NULL, INDEX IDX_B6BD307FF675F31B (author_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE mind (id INT AUTO_INCREMENT NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE model (id INT AUTO_INCREMENT NOT NULL, brand_id INT NOT NULL, year INT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', name_number VARCHAR(255) NOT NULL, INDEX IDX_D79572D944F5D008 (brand_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE practice (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE profile_image (id INT AUTO_INCREMENT NOT NULL, file_name VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ride (id INT AUTO_INCREMENT NOT NULL, mind_id INT NOT NULL, practice_id INT NOT NULL, creator_id INT NOT NULL, start_city_id INT NOT NULL, distance INT NOT NULL, ascent INT NOT NULL, max_participants INT NOT NULL, average_speed INT NOT NULL, start_date DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', title VARCHAR(255) NOT NULL, INDEX IDX_9B3D7CD053B01993 (mind_id), INDEX IDX_9B3D7CD0ED33821 (practice_id), INDEX IDX_9B3D7CD061220EA6 (creator_id), INDEX IDX_9B3D7CD07B693E7C (start_city_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ride_user (ride_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_C6ACE33D302A8A70 (ride_id), INDEX IDX_C6ACE33DA76ED395 (user_id), PRIMARY KEY(ride_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE ride_comment (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, ride_id INT NOT NULL, text VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_D3DB7D20F675F31B (author_id), INDEX IDX_D3DB7D20302A8A70 (ride_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE `user` (id INT AUTO_INCREMENT NOT NULL, mind_id INT DEFAULT NULL, practice_id INT DEFAULT NULL, bike_id INT DEFAULT NULL, department_id INT DEFAULT NULL, profile_image_id INT DEFAULT NULL, roles JSON NOT NULL, password VARCHAR(255) DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, birth_date DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', email VARCHAR(255) NOT NULL, token VARCHAR(255) DEFAULT NULL, is_verified TINYINT(1) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', updated_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', name_number VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), INDEX IDX_8D93D64953B01993 (mind_id), INDEX IDX_8D93D649ED33821 (practice_id), INDEX IDX_8D93D649D5A4816F (bike_id), INDEX IDX_8D93D649AE80F5DF (department_id), UNIQUE INDEX UNIQ_8D93D649C4CF44DC (profile_image_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', available_at DATETIME NOT NULL COMMENT '(DC2Type:datetime_immutable)', delivered_at DATETIME DEFAULT NULL COMMENT '(DC2Type:datetime_immutable)', INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE city ADD CONSTRAINT FK_2D5B0234AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message ADD CONSTRAINT FK_B6BD307FF675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE model ADD CONSTRAINT FK_D79572D944F5D008 FOREIGN KEY (brand_id) REFERENCES brand (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD053B01993 FOREIGN KEY (mind_id) REFERENCES mind (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD0ED33821 FOREIGN KEY (practice_id) REFERENCES practice (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD061220EA6 FOREIGN KEY (creator_id) REFERENCES `user` (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride ADD CONSTRAINT FK_9B3D7CD07B693E7C FOREIGN KEY (start_city_id) REFERENCES city (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_user ADD CONSTRAINT FK_C6ACE33D302A8A70 FOREIGN KEY (ride_id) REFERENCES ride (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_user ADD CONSTRAINT FK_C6ACE33DA76ED395 FOREIGN KEY (user_id) REFERENCES `user` (id) ON DELETE CASCADE
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_comment ADD CONSTRAINT FK_D3DB7D20F675F31B FOREIGN KEY (author_id) REFERENCES `user` (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_comment ADD CONSTRAINT FK_D3DB7D20302A8A70 FOREIGN KEY (ride_id) REFERENCES ride (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` ADD CONSTRAINT FK_8D93D64953B01993 FOREIGN KEY (mind_id) REFERENCES mind (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649ED33821 FOREIGN KEY (practice_id) REFERENCES practice (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649D5A4816F FOREIGN KEY (bike_id) REFERENCES model (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649AE80F5DF FOREIGN KEY (department_id) REFERENCES department (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` ADD CONSTRAINT FK_8D93D649C4CF44DC FOREIGN KEY (profile_image_id) REFERENCES profile_image (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE city DROP FOREIGN KEY FK_2D5B0234AE80F5DF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE message DROP FOREIGN KEY FK_B6BD307FF675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE model DROP FOREIGN KEY FK_D79572D944F5D008
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD053B01993
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD0ED33821
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD061220EA6
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride DROP FOREIGN KEY FK_9B3D7CD07B693E7C
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_user DROP FOREIGN KEY FK_C6ACE33D302A8A70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_user DROP FOREIGN KEY FK_C6ACE33DA76ED395
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_comment DROP FOREIGN KEY FK_D3DB7D20F675F31B
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE ride_comment DROP FOREIGN KEY FK_D3DB7D20302A8A70
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D64953B01993
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649ED33821
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649D5A4816F
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649AE80F5DF
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE `user` DROP FOREIGN KEY FK_8D93D649C4CF44DC
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE brand
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE city
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE department
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE message
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE mind
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE model
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE practice
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE profile_image
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ride
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ride_user
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE ride_comment
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE `user`
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE messenger_messages
        SQL);
    }
}
