<?php


namespace Nassau\CartoonBattle\Form;

use Nassau\CartoonBattle\Entity\Game\UserFarming;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FarmingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('enabled', CheckboxType::class, [
            'label' => 'Enable farming',
        ]);

        $builder->add('settings', ChoiceType::class, [
            'label' => 'Choose your chores',
            'choices_as_values' => true,
            'choices' => [
                'Adventure' => UserFarming::SETTING_ADVENTURE,
                'Arena' => UserFarming::SETTING_ARENA,
                'Challenges' => UserFarming::SETTING_CHALLENGES,
                'Daily quests (buy 3 basic packs and do one upgrade on each)' => UserFarming::SETTING_CARDS,
                'Spend all nixons above 100k and recycle everything under epic' => UserFarming::SETTING_GOLD,
            ],
            'multiple' => true,
        ]);

        $missions = array_map(function ($idx) {
            return sprintf('%s-%s', ceil($idx / 3), $idx % 3 ?: 3);
        }, range(1, 30*3));


        $builder->add('adventureMissions', ChoiceType::class, [
            'label' => 'Choose your desired adventure islands',
            'choices' => array_combine($missions, $missions),
            'multiple' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UserFarming::class,
        ]);
    }


}