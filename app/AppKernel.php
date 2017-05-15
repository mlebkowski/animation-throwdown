<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = [
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new FOS\HttpCacheBundle\FOSHttpCacheBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Kunstmaan\UtilitiesBundle\KunstmaanUtilitiesBundle(),
            new Kunstmaan\NodeBundle\KunstmaanNodeBundle(),
            new Kunstmaan\SeoBundle\KunstmaanSeoBundle(),
            new Symfony\Cmf\Bundle\RoutingBundle\CmfRoutingBundle(),
            new Stof\DoctrineExtensionsBundle\StofDoctrineExtensionsBundle(),
            new Liip\ImagineBundle\LiipImagineBundle(),
            new Knp\Bundle\GaufretteBundle\KnpGaufretteBundle(),
            new Kunstmaan\MediaBundle\KunstmaanMediaBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle(),
            new Doctrine\Bundle\MigrationsBundle\DoctrineMigrationsBundle(),
            new Doctrine\Bundle\DoctrineCacheBundle\DoctrineCacheBundle(),
            new Kunstmaan\AdminBundle\KunstmaanAdminBundle(),
            new Kunstmaan\PagePartBundle\KunstmaanPagePartBundle(),
            new Kunstmaan\AdminListBundle\KunstmaanAdminListBundle(),
            new Kunstmaan\MenuBundle\KunstmaanMenuBundle(),
            new WhiteOctober\PagerfantaBundle\WhiteOctoberPagerfantaBundle(),
            new Kunstmaan\SitemapBundle\KunstmaanSitemapBundle(),
            new Kunstmaan\RedirectBundle\KunstmaanRedirectBundle(),
            new Kunstmaan\UserManagementBundle\KunstmaanUserManagementBundle(),
            new Kunstmaan\DashboardBundle\KunstmaanDashboardBundle(),
            new ArsThanea\PageMediaSetBundle\PageMediaSetBundle(),
            new ArsThanea\KunstmaanExtraBundle\KunstmaanExtraBundle(),
            new ArsThanea\RemoteMediaBundle\RemoteMediaBundle(),

            new Nassau\CartoonBattle\CartoonBattleBundle(),
        ];

        if (in_array($this->getEnvironment(), ['dev'], true)) {
            $bundles[] = new Kunstmaan\GeneratorBundle\KunstmaanGeneratorBundle();
        }

        if (in_array($this->getEnvironment(), ['dev', 'test'], true)) {
            $bundles[] = new Symfony\Bundle\DebugBundle\DebugBundle();
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load($this->getRootDir() . '/config/config_' . $this->getEnvironment() . '.yml');
    }
}
