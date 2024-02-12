<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'answers')]
class Answer
{
    #[Id, Column, GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    #[Column(length: 255)]
    private string $text;

    #[Column]
    private bool $isCorrect;

    #[ManyToOne(targetEntity: Question::class)]
    private Question $question;

    public function __construct(string $answer, bool $isCorrect, Question $question)
    {
        $this->text = $answer;
        $this->isCorrect = $isCorrect;
        $this->question = $question;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getText(): string
    {
        return $this->text;
    }

    public function setText(string $text): void
    {
        $this->text = $text;
    }

    public function isCorrect(): bool
    {
        return $this->isCorrect;
    }

    public function setIsCorrect(bool $isCorrect): void
    {
        $this->isCorrect = $isCorrect;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): void
    {
        $this->question = $question;
    }
}
