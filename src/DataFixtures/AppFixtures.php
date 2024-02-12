<?php

namespace App\DataFixtures;

use App\Entity\Answer;
use App\Entity\TestItem;
use App\Entity\Question;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public const array TESTS = [
        [
            'questions' => [
                [
                    'text' => '1 + 1',
                    'answers' => [
                        ['text' => '3', 'is_correct' => false],
                        ['text' => '2', 'is_correct' => true],
                        ['text' => '0', 'is_correct' => false],
                    ],
                ],
                [
                    'text' => '2 + 2',
                    'answers' => [
                        ['text' => '4', 'is_correct' => true],
                        ['text' => '3 + 1', 'is_correct' => true],
                        ['text' => '10', 'is_correct' => false],
                    ],
                ],
                [
                    'text' => '3 + 3',
                    'answers' => [
                        ['text' => '1 + 5', 'is_correct' => true],
                        ['text' => '1', 'is_correct' => false],
                        ['text' => '6', 'is_correct' => true],
                        ['text' => '2 + 4', 'is_correct' => true],
                    ],
                ],
                [
                    'text' => '4 + 4',
                    'answers' => [
                        ['text' => '8', 'is_correct' => true],
                        ['text' => '4', 'is_correct' => false],
                        ['text' => '0', 'is_correct' => false],
                        ['text' => '0 + 8', 'is_correct' => true],
                    ],
                ],
                [
                    'text' => '5 + 5',
                    'answers' => [
                        ['text' => '6', 'is_correct' => false],
                        ['text' => '18', 'is_correct' => false],
                        ['text' => '10', 'is_correct' => true],
                        ['text' => '9', 'is_correct' => false],
                        ['text' => '0', 'is_correct' => false],
                    ],
                ],
                [
                    'text' => '6 + 6',
                    'answers' => [
                        ['text' => '3', 'is_correct' => false],
                        ['text' => '9', 'is_correct' => false],
                        ['text' => '0', 'is_correct' => false],
                        ['text' => '12', 'is_correct' => true],
                        ['text' => '5 + 7', 'is_correct' => true],
                    ],
                ],
                [
                    'text' => '7 + 7',
                    'answers' => [
                        ['text' => '5', 'is_correct' => false],
                        ['text' => '14', 'is_correct' => true],
                    ],
                ],
                [
                    'text' => '8 + 8',
                    'answers' => [
                        ['text' => '16', 'is_correct' => true],
                        ['text' => '12', 'is_correct' => false],
                        ['text' => '9', 'is_correct' => false],
                        ['text' => '5', 'is_correct' => false],
                    ],
                ],
                [
                    'text' => '9 + 9',
                    'answers' => [
                        ['text' => '18', 'is_correct' => true],
                        ['text' => '9', 'is_correct' => false],
                        ['text' => '17 + 1', 'is_correct' => true],
                        ['text' => '2 + 16', 'is_correct' => true],
                    ],
                ],
                [
                    'text' => '10 + 10',
                    'answers' => [
                        ['text' => '0', 'is_correct' => false],
                        ['text' => '2', 'is_correct' => false],
                        ['text' => '8', 'is_correct' => false],
                        ['text' => '20', 'is_correct' => true],
                    ],
                ],
            ],
        ],
    ];

    public function load(ObjectManager $manager): void
    {
        foreach (self::TESTS as $test) {
            $testItemEntity = new TestItem();
            $manager->persist($testItemEntity);
            foreach ($test['questions'] as $question) {
                $questionEntity = new Question($question['text'], $testItemEntity);
                $manager->persist($questionEntity);
                foreach ($question['answers'] as $answer) {
                    $answerEntity = new Answer(
                        $answer['text'],
                        $answer['is_correct'],
                        $questionEntity
                    );
                    $manager->persist($answerEntity);
                }
            }
        }

        $manager->flush();
    }
}
