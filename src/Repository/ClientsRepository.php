<?php

namespace App\Repository;


use App\Entity\Clients;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\OptimisticLockException;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;

class ClientsRepository extends ServiceEntityRepository

{ private $manager;
    public function __construct(ManagerRegistry $registry,EntityManagerInterface $manager)
    {
        parent::__construct($registry, Clients::class);
        $this->manager = $manager;
    }

    public function transformAll()
    {
        $clients = $this->findAll();
        $clientsArray = [];

        foreach ($clients as $client) {
            $clientArray[] = $this->transform($client);
        }

        return $clientArray;
    }
    /**
     * @throws ORMException
     * @throws OptimisticLockException
     */


    public function transform(Clients $clients)
    {
        return [
            'id' => (integer)$clients->getId(),
            'username' => (string)$clients->getUsername(),
            'email' => (string)$clients->getEmail(),
            'password' => (string)$clients->getPassword()
        ];
    }
    public function updateClients(Clients $clients): Clients
    {
        $this->manager->persist($clients);
        $this->manager->flush();
        return $clients;
    }

    public function save(Clients $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Clients $entity, bool $flush = true): void
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
        if (!$user instanceof Clients) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }
}
