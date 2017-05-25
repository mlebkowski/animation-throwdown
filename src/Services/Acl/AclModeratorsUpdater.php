<?php

namespace Nassau\CartoonBattle\Services\Acl;

use Kunstmaan\AdminBundle\Helper\Security\Acl\Permission\MaskBuilder;
use Symfony\Component\Security\Acl\Domain\UserSecurityIdentity;
use Symfony\Component\Security\Acl\Exception\AclNotFoundException;
use Symfony\Component\Security\Acl\Model\EntryInterface;
use Symfony\Component\Security\Acl\Model\MutableAclProviderInterface;

class AclModeratorsUpdater
{
    /**
     * @var MutableAclProviderInterface
     */
    private $provider;

    public function __construct(MutableAclProviderInterface $provider)
    {
        $this->provider = $provider;
    }


    public function updateAcl(HasModeratorsInterface $entity)
    {
        $modified = false;

        try {
            $acl = $this->provider->findAcl($entity);
        } catch (AclNotFoundException $e) {
            $acl = $this->provider->createAcl($entity);
            $modified = true;
        }

        /** @var EntryInterface[] $aces */
        $aces = $acl->getObjectAces();

        $permissions = MaskBuilder::MASK_DELETE | MaskBuilder::MASK_EDIT | MaskBuilder::MASK_VIEW;

        foreach ($entity->getModerators() as $user) {
            $userIdentity = UserSecurityIdentity::fromAccount($user);
            foreach ($aces as $idx => $ace) {
                if ($ace->getSecurityIdentity()->equals($userIdentity)) {
                    if ($ace->getMask() !== $permissions) {
                        $acl->updateObjectAce($idx, $permissions);
                        $modified = true;

                    }

                    unset($aces[$idx]);

                    break 2; // break out of the user loop!
                }
            }

            $acl->insertObjectAce($userIdentity, $permissions);
            $modified = true;
        }

        foreach ($aces as $idx => $ace) {
            $acl->deleteObjectAce($idx);
            $modified = true;
        }

        if ($modified) {
            $this->provider->updateAcl($acl);
        }
    }
}
