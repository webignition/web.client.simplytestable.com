<?php

namespace SimplyTestable\WebClientBundle\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\HttpKernel\DependencyInjection\Extension;
use Symfony\Component\DependencyInjection\Loader;
use Symfony\Component\Yaml\Yaml;

/**
 * This is the class that loads and manages your bundle configuration
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html}
 */
class SimplyTestableWebClientExtension extends Extension
{
    /**
     * @var string[]
     */
    private $parameterFiles = [
        'task_types.yml',
        'mail.yml',
        'css-validation-ignore-common-cdns.yml',
        'js-static-analysis-ignore-common-cdns.yml',
        'plans.yml',
        'documentation_site.yml',
        'test_options.yml',
    ];

    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $configuration = new Configuration();
        $this->processConfiguration($configuration, $configs);

        $fileLocator = new FileLocator([
            __DIR__.'/../Resources/config',
            __DIR__.'/../Resources/config/parameters',
        ]);

        $loader = new Loader\YamlFileLoader($container, $fileLocator);
        $loader->load('services.yml');

        foreach ($this->parameterFiles as $parameterFile) {
            $parameterName = str_replace('.yml', '', $parameterFile);

            $container->setParameter(
                $parameterName,
                Yaml::parse(file_get_contents($fileLocator->locate($parameterFile)))
            );
        }
    }
}
