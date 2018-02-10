<?php

namespace App\Repository;

use App\Entity\Avatar;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * Class AvatarRepository
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class AvatarRepository extends ServiceEntityRepository
{
    /**
     * AvatarRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Avatar::class);
    }
}
