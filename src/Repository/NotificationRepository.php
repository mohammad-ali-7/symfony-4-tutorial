<?php

namespace App\Repository;

use App\Entity\LikeNotification;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class NotificationRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, LikeNotification::class);
    }

    /**
     * @param User $user
     * @return mixed
     * @throws \Doctrine\ORM\NonUniqueResultException
     */
    public function findUnseenByUser(User $user)
    {
        $qb = $this->createQueryBuilder('n');

        return $qb->select('count(n)')
            ->where('n.user = :user')
            ->andWhere('n.seen = 0')
            ->setParameter('user', $user)
            ->getQuery()
            ->getSingleScalarResult();
    }

    /**
     * @param User $user
     */
    public function markAllAsReadByUser(User $user): void
    {
        $qb = $this->createQueryBuilder('n');
        $qb->update('App\Entity\Notification', 'n')
            ->set('n.seen', true)
            ->where('n.user = :user')
            ->setParameter('user', $user)
            ->getQuery()
            ->execute();
    }
}
