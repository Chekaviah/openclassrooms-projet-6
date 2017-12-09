<?php

namespace App\DataFixtures;


use App\Entity\Category;
use App\Entity\Trick;
use App\Service\Slugger;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class TrickFixtures extends Fixture
{
	/**
	 * @param ObjectManager $manager
	 */
	public function load(ObjectManager $manager)
	{
		$tricks = array(
			array(
				'name' => 'BS 540 Seatbelt',
				'description' => 'Hitsch aurait tout aussi bien pu faire de la danse classique mais il s’est décidé pour la neige. Peut-être tout simplement parce qu’en Engadine, 
				les montagnes sont plus séduisantes que les gymnases. Quoi qu’il en soit, quiconque arrive à attraper aussi tranquillement l’arrière de la planche avec la main avant 
				pendant un BS 5 dans un half-pipe sans s’ouvrir les lèvres sur le coping devrait occuper une chaire à Cambridge sur les prodiges de la coordination.',
				'categories' => ['grabs'],
			),
			array(
				'name' => 'FS 900',
				'description' => 'Quand le style est vraiment original, on le reconnaît même dans les tricks banals. Vous en voulez l’exemple parfait? Travis Parker. Il fait un FS 900 
					(aujourd’hui aussi banal que l’était l’indy il y a 20 ans) depuis la carre front, tient le nose et pendant un instant le monde s’immobilise. Que Travis soit original 
					est de toute manière indiscutable. Qui d’autre annule du jour au lendemain les contrats avec tous ses sponsors pour devenir cuisinier de sushis?',
				'categories' => ['grabs'],
			),
			array(
				'name' => 'Backside Air',
				'description' => 'On commence tout simplement avec LE trick. Les mauvaises langues prétendent qu’un backside air suffit à reconnaître ceux qui savent snowboarder. 
					Si c’est vrai, alors Nicolas Müller est le meilleur snowboardeur du monde. Personne ne sait s’étirer aussi joliment, ne demeure aussi zen, n’est aussi provocant 
					dans la jouissance.',
				'categories' => ['rotations'],
			),
			array(
				'name' => 'Backside Triple Cork 1440',
				'description' => 'En langage snowboard, un cork est une rotation horizontale plus ou moins désaxée, selon un mouvement d\'épaules effectué juste au moment 
					du saut. Le tout premier Triple Cork a été plaqué par Mark McMorris en 2011, lequel a récidivé lors des Winter X Games 2012... avant de se faire voler la 
					vedette par Torstein Horgmo, lors de ce même championnat. Le Norvégien réalisa son propre Backside Triple Cork 1440 et obtint la note parfaite de 50/50.',
				'categories' => ['rotations'],
			),
			array(
				'name' => 'Method Air',
				'description' => 'Cette figure – qui consiste à attraper sa planche d\'une main et le tourner perpendiculairement au sol – est un classique "old school". 
					Il n\'empêche qu\'il est indémodable, avec de vrais ambassadeurs comme Jamie Lynn ou la star Terje Haakonsen. En 2007, ce dernier a même battu le 
					record du monde du "air" le plus haut en s\'élevant à 9,8 mètres au-dessus du kick (sommet d\'un mur d\'une rampe ou autre structure de saut).',
				'categories' => ['old-school'],
			),
			array(
				'name' => 'Double Backflip One Foot',
				'description' => 'Comme on peut le deviner, les "one foot" sont des figures réalisées avec un pied décroché de la fixation. Pendant le saut, le 
					snowboarder doit tendre la jambe du côté dudit pied. L\'esthète Scotty Vine – une sorte de Danny MacAskill du snowboard – en a réalisé un bel exemple 
					avec son Double Backflip One Foot.',
				'categories' => ['one-foot', 'flips'],
			),
			array(
				'name' => 'Double Mc Twist 1260',
				'description' => 'Le Mc Twist est un flip (rotation verticale) agrémenté d\'une vrille. Un saut très périlleux réservé aux professionnels. Le champion 
					précoce Shaun White s\'est illustré par un Double Mc Twist 1260 lors de sa session de Half-Pipe aux Jeux Olympiques de Vancouver en 2010. Nul doute 
					que c\'est cette figure qui lui a valu de remporter la médaille d\'or.',
				'categories' => ['flips'],
			),
			array(
				'name' => 'Double Backside Rodeo 1080',
				'description' => 'À l\'instar du cork, le rodeo est une rotation désaxée, qui se reconnaît par son aspect vrillé. Un des plus beaux de l\'histoire est 
					sans au cun doute le Double Backside Rodeo 1080 effectué pour la première fois en compétition par le jeune prodige Travis Rice, lors du Icer Air 2007. 
					La pirouette est tellement culte qu\'elle a fini dans un jeu vidéo où Travis Rice est l\'un des personnages.',
				'categories' => ['rotations-desaxees'],
			),
		);

		foreach ($tricks as $d) {
			$slug = Slugger::slugify($d['name']);

			$trick = new Trick();
			$trick->setName($d['name']);
			$trick->setSlug($slug);
			$trick->setDescription($d['description']);
			foreach ($d['categories'] as $c) {
				/** @var Category $category */
				$category = $this->getReference('category-'.$c);
				$trick->addCategory($category);
			}

			$this->addReference('trick-'.$slug, $trick);

			$manager->persist($trick);
		}

		$manager->flush();
	}

	public function getDependencies()
	{
		return array(
			CategoryFixtures::class,
		);
	}
}