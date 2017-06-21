<?php

namespace Nassau\CartoonBattle\Form;

use Kunstmaan\AdminBundle\Entity\User;
use Kunstmaan\AdminBundle\Form\WysiwygType;
use Kunstmaan\AdminBundle\Helper\Security\Acl\Permission\PermissionMap;
use Nassau\CartoonBattle\Entity\Guild\Guild;
use Nassau\CartoonBattle\Services\Game\Game;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Validator\Constraints\NotBlank;

class GuildAdminType extends AbstractType
{
    /**
     * @var AuthorizationCheckerInterface
     */
    private $authorizationChecker;

    /**
     * @var Game
     */
    private $game;

    public function __construct(AuthorizationCheckerInterface $authorizationChecker, Game $game = null)
    {
        $this->authorizationChecker = $authorizationChecker;
        $this->game = $game;
    }


    /**
     * Builds the form.
     *
     * This method is called for each type in the hierarchy starting form the
     * top most type. Type extensions can further modify the form.
     *
     * @see FormTypeExtensionInterface::buildForm()
     *
     * @param FormBuilderInterface $builder The form builder
     * @param array                $options The options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('name', TextType::class, [
            'label' => 'Name',
            'constraints' => new NotBlank(),
        ]);

        $builder->add('faction_id', TextType::class, [
            'label' => 'In-game guild id',
            'required' => true,
            'constraints' => new NotBlank(),
        ]);

        $builder->add('recruiting', CheckboxType::class, [
            'label' => 'Currently recruiting',
            'required' => false,
        ]);

        $builder->add('message', WysiwygType::class, [
            'label' => 'Custom guild message',
        ]);

        $builder->add('url', UrlType::class, [
            'label' => 'Guild’s website, discord, recruitment thread or other URL',
            'required' => false,
        ]);

        $builder->add('standings', CollectionType::class, [
            'label' => 'Rumble positions',
            'entry_type' => RumbleStandingAdminType::class,
            'entry_options' => [
                'relation_to' => RumbleStandingAdminType::RELATION_TO_GUILD
            ],
            'allow_delete' => true,
            'allow_add' => true,
            'by_reference' => false,
            'attr' => [
                'nested_form' => true,
            ],
        ]);

        $builder->add('moderators', EntityType::class, [
            'label' => 'Users with access to edit this guild',
            'required' => false,
            'class' => User::class,
            'multiple' => true,
            'expanded' => false,
            'attr' => [
                'class' => 'js-advanced-select',
            ],
            'by_reference' => false,
        ]);

        $builder->addEventListener(FormEvents::POST_SET_DATA, function (FormEvent $event) {
            if (false === $this->authorizationChecker->isGranted(PermissionMap::PERMISSION_PUBLISH, $event->getData())) {
                $event->getForm()->remove('moderators')->remove('standings');
            }
        });

        $builder->addEventListener(FormEvents::PRE_SUBMIT, function (FormEvent $event) {
            $factionId = $event->getData()['faction_id'];
            $name = $event->getData()['name'];

            if ("" !== $factionId || "" === $name) {
                return;
            }

            $matches = $this->game->searchGuildName($name);
            if (0 === sizeof($matches)) {
                return;
            }

            if (1 === sizeof($matches)) {
                /** @var Guild $guild */
                list ($guild) = $matches;
                $event->setData(['faction_id' => $guild->getId()] + $event->getData());

                return;
            }

            $event->getForm()->add('faction_id', ChoiceType::class, [
                'label' => 'Select a guild matching your name',
                'choices' => $matches,
                'choices_as_values' => true,
                'choice_label' => 'name',
                'choice_value' => 'id',
                'constraints' => new NotBlank(),
            ]);
        });
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Guild::class,
        ]);
    }


    /**
     * Returns the name of this type.
     *
     * @return string The name of this type
     */
    public function getBlockPrefix()
    {
        return 'guild_form';
    }
}
