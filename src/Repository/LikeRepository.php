<?php

namespace App\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\AbstractQuery;

class LikeRepository extends EntityRepository {

    public function getLikesByComment(int $commentId) {
        $qb = $this->createQueryBuilder('l')
            ->select('count(l.isLike)')
            ->where('l.comentario = :commentId')
            ->andWhere('l.isLike = :like')
            ->setParameter('commentId', $commentId)
            ->setParameter('like', true);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getDisLikesByComment(int $commentId) {
        $qb = $this->createQueryBuilder('l')
            ->select('count(l.isLike)')
            ->where('l.comentario = :commentId')
            ->andWhere('l.isLike = :like')
            ->setParameter('commentId', $commentId)
            ->setParameter('like', false);

        return $qb->getQuery()->getSingleScalarResult();
    }

    public function getLikeByCommentAndUser(int $commentId, int $userId) {
        $qb = $this->createQueryBuilder('l')
            ->select('l.id')
            ->where('l.comentario = :commentId')
            ->andWhere('l.isLike = :like')
            ->andWhere('l.user = :user')
            ->setParameter('commentId', $commentId)
            ->setParameter('like', true)
            ->setParameter('user', $userId);

        return $qb->getQuery()->getResult();
    }

    public function getUnLikeByCommentAndUser(int $commentId, int $userId) {
        $qb = $this->createQueryBuilder('l')
            ->select('l.id')
            ->where('l.comentario = :commentId')
            ->andWhere('l.isLike = :like')
            ->andWhere('l.user = :user')
            ->setParameter('commentId', $commentId)
            ->setParameter('like', false)
            ->setParameter('user', $userId);

        return $qb->getQuery()->getResult();
    }
}