<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200806122106 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE apartment_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE bed_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE booking_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE apartment (id INT NOT NULL, name VARCHAR(255) NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE bed (id INT NOT NULL, apartment_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_E647FCFF176DFE85 ON bed (apartment_id)');
        $this->addSql('CREATE TABLE booking (id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, start_date DATE NOT NULL, end_date DATE NOT NULL, price INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE booking_bed (booking_id INT NOT NULL, bed_id INT NOT NULL, PRIMARY KEY(booking_id, bed_id))');
        $this->addSql('CREATE INDEX IDX_C022C5283301C60 ON booking_bed (booking_id)');
        $this->addSql('CREATE INDEX IDX_C022C52888688BB9 ON booking_bed (bed_id)');
        $this->addSql('ALTER TABLE bed ADD CONSTRAINT FK_E647FCFF176DFE85 FOREIGN KEY (apartment_id) REFERENCES apartment (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking_bed ADD CONSTRAINT FK_C022C5283301C60 FOREIGN KEY (booking_id) REFERENCES booking (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE booking_bed ADD CONSTRAINT FK_C022C52888688BB9 FOREIGN KEY (bed_id) REFERENCES bed (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE bed DROP CONSTRAINT FK_E647FCFF176DFE85');
        $this->addSql('ALTER TABLE booking_bed DROP CONSTRAINT FK_C022C52888688BB9');
        $this->addSql('ALTER TABLE booking_bed DROP CONSTRAINT FK_C022C5283301C60');
        $this->addSql('DROP SEQUENCE apartment_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE bed_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE booking_id_seq CASCADE');
        $this->addSql('DROP TABLE apartment');
        $this->addSql('DROP TABLE bed');
        $this->addSql('DROP TABLE booking');
        $this->addSql('DROP TABLE booking_bed');
    }
}
