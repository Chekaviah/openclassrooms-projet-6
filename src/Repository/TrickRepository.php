<?php

namespace App\Repository;

use App\Entity\Trick;
use Symfony\Bridge\Doctrine\RegistryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

/**
 * Class TrickRepository
 *
 * @author Mathieu GUILLEMINOT <guilleminotm@gmail.com>
 */
class TrickRepository extends ServiceEntityRepository
{
    /**
     * TrickRepository constructor.
     *
     * @param RegistryInterface $registry
     */
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Trick::class);
    }

	/**
	 * @return mixed
	 */
    public function findAllWithData()
	{
    	return $this->createQueryBuilder('t')
			->leftJoin('t.images', 'i')
			->addSelect('i')
			->leftJoin('t.videos', 'v')
			->addSelect('v')
			->leftJoin('t.categories', 'c')
			->addSelect('c')
			->getQuery()
			->getResult();
	}

	/**
	 * @param string $slug
     *
	 * @return mixed
	 */
	public function findOneBySlugWithData(string $slug)
	{
		return $this->createQueryBuilder('t')
			->where('t.slug = :slug')
			->setParameter('slug', $slug)
			->leftJoin('t.images', 'i')
			->addSelect('i')
			->leftJoin('t.videos', 'v')
			->addSelect('v')
			->leftJoin('t.categories', 'c')
			->addSelect('c')
			->leftJoin('t.comments', 'co')
			->addSelect('co')
			->getQuery()
			->getOneOrNullResult();
	}

	/**
	 * @param int $id
     *
	 * @return mixed
	 */
	public function findOneByIdWithData(int $id)
	{
		return $this->createQueryBuilder('t')
			->where('t.id = :id')
			->setParameter('id', $id)
			->leftJoin('t.images', 'i')
			->addSelect('i')
			->leftJoin('t.videos', 'v')
			->addSelect('v')
			->leftJoin('t.categories', 'c')
			->addSelect('c')
			->getQuery()
			->getOneOrNullResult();
	}
}
