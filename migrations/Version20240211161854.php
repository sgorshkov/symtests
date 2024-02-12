<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240211161854 extends AbstractMigration
{
    public function getDescription(): string
    {
        return 'Create tests, questions, answers, answer_variants tables';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE answer_variants_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE answers_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE questions_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE tests_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE answer_variants (id INT NOT NULL, question_id INT DEFAULT NULL, session_id VARCHAR(20) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_94D252841E27F6BF ON answer_variants (question_id)');
        $this->addSql('CREATE TABLE answer_variant_answer (answer_variant_id INT NOT NULL, answer_id INT NOT NULL, PRIMARY KEY(answer_variant_id, answer_id))');
        $this->addSql('CREATE INDEX IDX_6375B29654E42191 ON answer_variant_answer (answer_variant_id)');
        $this->addSql('CREATE INDEX IDX_6375B296AA334807 ON answer_variant_answer (answer_id)');
        $this->addSql('CREATE TABLE answers (id INT NOT NULL, question_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, is_correct BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_50D0C6061E27F6BF ON answers (question_id)');
        $this->addSql('CREATE TABLE questions (id INT NOT NULL, test_id INT DEFAULT NULL, text VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_8ADC54D51E5D0459 ON questions (test_id)');
        $this->addSql('CREATE TABLE tests (id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE answer_variants ADD CONSTRAINT FK_94D252841E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE answer_variant_answer ADD CONSTRAINT FK_6375B29654E42191 FOREIGN KEY (answer_variant_id) REFERENCES answer_variants (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE answer_variant_answer ADD CONSTRAINT FK_6375B296AA334807 FOREIGN KEY (answer_id) REFERENCES answers (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE answers ADD CONSTRAINT FK_50D0C6061E27F6BF FOREIGN KEY (question_id) REFERENCES questions (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE questions ADD CONSTRAINT FK_8ADC54D51E5D0459 FOREIGN KEY (test_id) REFERENCES tests (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP SEQUENCE answer_variants_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE answers_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE questions_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE tests_id_seq CASCADE');
        $this->addSql('ALTER TABLE answer_variants DROP CONSTRAINT FK_94D252841E27F6BF');
        $this->addSql('ALTER TABLE answer_variant_answer DROP CONSTRAINT FK_6375B29654E42191');
        $this->addSql('ALTER TABLE answer_variant_answer DROP CONSTRAINT FK_6375B296AA334807');
        $this->addSql('ALTER TABLE answers DROP CONSTRAINT FK_50D0C6061E27F6BF');
        $this->addSql('ALTER TABLE questions DROP CONSTRAINT FK_8ADC54D51E5D0459');
        $this->addSql('DROP TABLE answer_variants');
        $this->addSql('DROP TABLE answer_variant_answer');
        $this->addSql('DROP TABLE answers');
        $this->addSql('DROP TABLE questions');
        $this->addSql('DROP TABLE tests');
    }
}
