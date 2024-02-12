<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Answer;
use App\Entity\AnswerVariant;
use App\Entity\Question;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\ResultSetMapping;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AnswerVariant|null find($id, $lockMode = null, $lockVersion = null)
 * @method AnswerVariant|null findOneBy(array $criteria, array $orderBy = null)
 * @method AnswerVariant[]    findAll()
 * @method AnswerVariant[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AnswerVariantRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AnswerVariant::class);
    }

    /**
     * Receive grouped test results by session.
     */
    public function getResultsBySessionId(string $sessionId): array
    {
        $entityManager = $this->getEntityManager();

        $rsm = new ResultSetMapping();
        $rsm->addScalarResult('id', 'id');
        $rsm->addScalarResult('question', 'question');
        $rsm->addScalarResult('is_success', 'is_success');

        $answerVariantClassMetadata = $entityManager->getClassMetadata(AnswerVariant::class);
        $answerVariantTableName = $answerVariantClassMetadata->getTableName();
        $answerVariantAnswersTableName = $answerVariantClassMetadata
            ->getAssociationMapping('answers')['joinTable']['name'];
        $answersTableName = $entityManager->getClassMetadata(Answer::class)->getTableName();
        $questionTableName = $entityManager->getClassMetadata(Question::class)->getTableName();

        $sql = 'SELECT q.id, q.text AS question, MIN(a.is_correct::int)::bool AS is_success
            FROM ' . $answerVariantTableName . ' AS av'
            . ' INNER JOIN ' . $answerVariantAnswersTableName . ' AS ava ON ava.answer_variant_id=av.id'
            . ' INNER JOIN ' . $answersTableName . ' AS a ON a.id=ava.answer_id'
            . ' INNER JOIN ' . $questionTableName . ' AS q ON q.id=av.question_id'
            . ' WHERE av.session_id=:sessionId'
            . ' GROUP BY q.id';

        $query = $this->getEntityManager()
            ->createNativeQuery($sql, $rsm)
            ->setParameter('sessionId', $sessionId);

        return $query->getArrayResult();
    }
}
