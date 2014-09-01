<?php

use Symfony\Component\HttpKernel\Kernel;
use Symfony\Component\Config\Loader\LoaderInterface;

class AppKernel extends Kernel
{
    public function registerBundles()
    {
        $bundles = array(
            new Symfony\Bundle\FrameworkBundle\FrameworkBundle(),
            new Symfony\Bundle\SecurityBundle\SecurityBundle(),
            new Symfony\Bundle\TwigBundle\TwigBundle(),
            new Symfony\Bundle\MonologBundle\MonologBundle(),
            new Symfony\Bundle\SwiftmailerBundle\SwiftmailerBundle(),
            new Symfony\Bundle\AsseticBundle\AsseticBundle(),
            new Doctrine\Bundle\DoctrineBundle\DoctrineBundle(),
            new Sensio\Bundle\FrameworkExtraBundle\SensioFrameworkExtraBundle(),
            new Knp\Bundle\MenuBundle\KnpMenuBundle(),
            new FOS\UserBundle\FOSUserBundle(),
            new Yepsua\SecurityBundle\YepsuaSecurityBundle(),
            new Yepsua\MenuBundle\YepsuaMenuBundle(),
            new Yepsua\SmarTwigBundle\YepsuaSmarTwigBundle(),
            new Yepsua\CommonsBundle\YepsuaCommonsBundle(),
            new Yepsua\GeneratorBundle\YepsuaGeneratorBundle(),
            new Yepsua\ThemeBundle\YepsuaThemeBundle(),
            new Yepsua\RADBundle\YepsuaRADBundle(),
            new Yepsua\LocalityBundle\YepsuaLocalityBundle(),
            new Yepsua\LOVBundle\YepsuaLOVBundle(),
            new OpenSkool\ThemeBundle\OpenSkoolThemeBundle(),
            new OpenSkool\CoreBundle\OpenSkoolCoreBundle(),
            new OpenSkool\AdminBundle\OpenSkoolAdminBundle(),
            new OpenSkool\PeopleBundle\OpenSkoolPeopleBundle(),
            new OpenSkool\SecurityBundle\OpenSkoolSecurityBundle(),
            new OpenSkool\LOVBundle\OpenSkoolLOVBundle(),
            new OpenSkool\LocalityBundle\OpenSkoolLocalityBundle(),
        );

        if (in_array($this->getEnvironment(), array('dev', 'test'))) {
            $bundles[] = new Symfony\Bundle\WebProfilerBundle\WebProfilerBundle();
            $bundles[] = new Sensio\Bundle\DistributionBundle\SensioDistributionBundle();
            $bundles[] = new Sensio\Bundle\GeneratorBundle\SensioGeneratorBundle();
            $bundles[] = new OpenSkool\StaticResourcesBundle\OpenSkoolStaticResourcesBundle();
            $bundles[] = new Doctrine\Bundle\FixturesBundle\DoctrineFixturesBundle();
            $bundles[] = new Hautelook\AliceBundle\HautelookAliceBundle();
        }

        return $bundles;
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__.'/config/config_'.$this->getEnvironment().'.yml');
    }
}
