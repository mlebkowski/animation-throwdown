<?php

namespace Nassau\CartoonBattle\Form\Farming;

use Doctrine\Common\Persistence\ObjectRepository;
use Nassau\CartoonBattle\Entity\Game\Hero;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FarmableHeroType extends AbstractType
{
    /**
     * @var ObjectRepository
     */
    private $repo;

    public function __construct(ObjectRepository $repo)
    {
        $this->repo = $repo;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $farmableHeroes = $this->repo->findBy(['farmable' => true]);

        $choices = array_combine(array_map(function (Hero $hero) {
            return $hero->getName();
        }, $farmableHeroes), array_map(function (Hero $hero) {
            return $hero->getId();
        }, $farmableHeroes));

        $resolver->setDefaults([
            'label' => 'Hero',
            'choices' => $choices,
            'choices_as_values' => true,
            'multiple' => true,
            'expanded' => false,
        ]);
    }


    public function getParent()
    {
        return ChoiceType::class;
    }


}