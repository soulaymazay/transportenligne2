<?php

namespace App\Repository;

use App\Entity\Chauff;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;

class ChauffRepository extends ServiceEntityRepository
{private $manager;

    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {
        parent::__construct($registry, Chauff::class);
        $this->manager = $manager;
    }

    public function transformAll()
    {
        $chauff = $this->findAll();
        $chauffArray = [];

        foreach ($chauff as $chauffs) {
            $chauffsArray[] = $this->transform($chauffs);
        }

        return $chauffsArray;
    }
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */


    public function transform(Chauff $chauff)
    {
        return [
            'id' => (integer)$chauff->getId(),
            'numpermis' => (string)$chauff->getNumpermis(),
            'username' => (string)$chauff->getUsername(),
            'email' => (string)$chauff->getEmail(),
            'password' => (string)$chauff->getPassword()
        ];
    }
    public function updateChauff(Chauff $chauff): Chauff
    {
        $this->manager->persist($chauff);
        $this->manager->flush();
        return $chauff;
    }

    public function save(Chauff $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Chauff $entity, bool $flush = true): void
    {
        $this->_em->remove($entity);
        if ($flush) {
            $this->_em->flush();
        }
    }
    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(UserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof Chauff) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
