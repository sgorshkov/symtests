<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\AnswerVariantRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: AnswerVariantRepository::class)]
#[Table(name: 'answer_variants')]
class AnswerVariant
{
    #[Id, Column, GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    #[Column(length: 20)]
    private string $sessionId;

    #[ManyToOne(targetEntity: Question::class)]
    private Question $question;

    #[ManyToMany(targetEntity: Answer::class)]
    private Collection $answers;

    /**
     * @param Answer[] $answers
     */
    public function __construct(string $sessionId, Question $question, array $answers)
    {
        $this->sessionId = $sessionId;
        $this->question = $question;
        $this->answers = new ArrayCollection($answers);
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSessionId(): string
    {
        return $this->sessionId;
    }

    public function setSessionId(string $sessionId): void
    {
        $this->sessionId = $sessionId;
    }

    public function getQuestion(): Question
    {
        return $this->question;
    }

    public function setQuestion(Question $question): void
    {
        $this->question = $question;
    }

    /**
     * @return Answer[]
     */
    public function getAnswers(): array
    {
        return $this->answers->toArray();
    }
}
