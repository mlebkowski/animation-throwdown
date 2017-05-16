<?php

namespace Nassau\CartoonBattle\Form;

use Nassau\CartoonBattle\Entity\Guild\Guild;
use Nassau\CartoonBattle\Entity\Rumble\Rumble;
use Nassau\CartoonBattle\Entity\Rumble\RumbleStanding;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Range;

class RumbleStandingAdminType extends AbstractType
{
    const RELATION_TO_GUILD = 'guild';

    const RELATION_TO_RUMBLE = 'rumble';

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('place', TextType::class, [
            'label' => 'Final ranking position',
            'constraints' => [new Range(['min' => 1, 'max' => 250]), new NotBlank()],
        ]);

        if (self::RELATION_TO_RUMBLE === $options['relation_to']) {
            $builder->add('guild', EntityType::class, [
                'class' => Guild::class,
                'empty_value' => ' ',
                'label' => 'Guild name',
                'constraints' => new NotBlank(),
                'attr' => [
                    'class' => 'js-advanced-select',
                ],
            ]);
        }

        if (self::RELATION_TO_GUILD === $options['relation_to']) {
            $builder->add('rumble', EntityType::class, [
                'class' => Rumble::class,
                'empty_value' => ' ',
                'label' => 'Rumble',
                'constraints' => new NotBlank(),
                'attr' => [
                    'class' => 'js-advanced-select',
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => RumbleStanding::class,
            'relation_to' => self::RELATION_TO_RUMBLE,
        ]);

        $resolver->setAllowedValues('relation_to', [self::RELATION_TO_GUILD, self::RELATION_TO_RUMBLE]);
    }


    public function getBlockPrefix()
    {
        return 'rumble_standing_form';
    }

}
