<?php

namespace App\DataPersister;

use ApiPlatform\Core\DataPersister\DataPersisterInterface;
use App\Entity\Master;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class MasterDataPersister implements DataPersisterInterface
{
    public function __construct(
        private EntityManagerInterface $manager,
        private UserPasswordHasherInterface $hasher
    ) { }

    public function supports($data): bool
    {
        return $data instanceof Master;
    }

    /** @param Master $data */
    public function persist($data)
    {
        if ($data->plainPassword) {
            $data->setPassword(
                $this->hasher->hashPassword($data, $data->plainPassword)
            );
            $data->eraseCredentials();
        }

        $this->manager->persist($data);
        $this->manager->flush();
    }

    public function remove($data)
    {
        $this->manager->remove($data);
        $this->manager->flush();
    }
}