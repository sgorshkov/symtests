<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity]
#[Table(name: 'questions')]
class Question
{
    #[Id, Column, GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    #[Column(length: 255)]
    private string $text;

    #[ManyToOne(targetEntity: TestItem::class)]
    private TestItem $test;

    #[OneToMany(
        mappedBy: 'question',
        targetEntity: Answer::class,
        fetch: 'EAGER'
    )]
    private Collection $answers;

    public function __construct(string $question, TestItem $test)
    {
        $this->text = $question;
        $this->test = $test;
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

    public function getTest(): TestItem
    {
        return $this->test;
    }

    public function setTest(TestItem $test): void
    {
        $this->test = $test;
    }

    /**
     * @return Answer[]
     */
    public function getAnswers(): array
    {
        return $this->answers->toArray();
    }
}
