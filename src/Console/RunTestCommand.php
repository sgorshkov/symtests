<?php

declare(strict_types=1);

namespace App\Console;

use App\Entity\AnswerVariant;
use App\Repository\AnswerVariantRepository;
use App\Repository\TestItemRepository;
use App\Util\TesterUtil;
use App\Util\TestItemUtil;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;

#[AsCommand(
    name: 'app:start-test',
    description: 'Run tests with multiple answers allowed'
)]
final class RunTestCommand extends Command
{
    private string $sessionId;
    private OutputInterface $output;

    public function __construct(
        private readonly TestItemRepository $testItemRepository,
        private readonly AnswerVariantRepository $answerVariantRepository,
        private readonly EntityManagerInterface $entityManager
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->addArgument(
            'testId',
            InputArgument::OPTIONAL,
            'TestItem ID to run or last one if argument not presented',
        );
        $this->addOption(
            name: 'no-shuffle',
            mode: InputOption::VALUE_NONE,
            description: 'Not shuffle questions and answers order'
        );
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->output = $output;

        $testId = $input->getArgument('testId');
        if ($testId) {
            $testItem = $this->testItemRepository->find($testId);
        } else {
            $testItem = $this->testItemRepository->findLastWithQuestionsAndAnswers();
        }
        if (!$testItem) {
            $output->writeln('Test not found');

            return Command::FAILURE;
        }

        $needShuffle = !$input->getOption('no-shuffle');
        $this->sessionId = TestItemUtil::generateName();

        $output->writeln(sprintf('Start test session: %s', $this->sessionId));
        $output->writeln(
            sprintf(
                'Test id: %d (shuffle mode: %s)',
                $testItem->getId(),
                $needShuffle ? 'enabled' : 'disabled'
            )
        );
        $questions = $testItem->getQuestions();
        if ($needShuffle) {
            shuffle($questions);
        }

        $helper = $this->getHelper('question');
        foreach ($questions as $questionEntity) {
            $questionText = '> ' . $questionEntity->getText();
            $answersEntities = $questionEntity->getAnswers();
            if ($needShuffle) {
                shuffle($answersEntities);
            }

            // prepare indexed choice prompt
            $choices = [];
            $i = 1;
            foreach ($answersEntities as $answer) {
                $choices[$i] = $answer->getText();
                $i++;
            }

            $question = new ChoiceQuestion($questionText, $choices);

            $question->setMultiselect(true);
            $question->setAutocompleterValues([]);
            $question->setPrompt(' you answer: ');
            // replace default validator to prevent comparing user input with answer text
            $question->setValidator(TesterUtil::multiselectValidator($choices));
            $chosenAnswers = $helper->ask($input, $output, $question);

            $variants = [];
            foreach ($chosenAnswers as $chosenAnswerIdx) {
                $variants[] = $answersEntities[$chosenAnswerIdx - 1];
            }

            $answerVariant = new AnswerVariant($this->sessionId, $questionEntity, $variants);
            $this->entityManager->persist($answerVariant);
        }
        $this->entityManager->flush();

        $output->writeln("\n");
        $this->printResults();
        $output->writeln("\n");

        return Command::SUCCESS;
    }

    private function printResults(): void
    {
        $results = $this->answerVariantRepository->getResultsBySessionId($this->sessionId);

        $this->output->writeln("Results: id: question - status");
        foreach ($results as $result) {
            $this->output->writeln(
                sprintf(
                    '> %d: %s - %s',
                    $result['id'],
                    $result['question'],
                    $result['is_success'] ? 'success' : 'fail'
                )
            );
        }
    }
}
