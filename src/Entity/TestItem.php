<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\TestItemRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;

#[Entity(repositoryClass: TestItemRepository::class)]
#[Table(name: 'tests')]
class TestItem
{
    #[Id, Column, GeneratedValue(strategy: 'SEQUENCE')]
    private ?int $id = null;

    #[OneToMany(
        mappedBy: 'test',
        targetEntity: Question::class,
        fetch: 'EAGER'
    )]
    private Collection $questions;

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Question[]
     */
    public function getQuestions(): array
    {
        return $this->questions->toArray();
    }
}
