<?php

namespace Alex\MailCatcher\Behat\MailCatcherExtension;

use Symfony\Component\Config\FileLocator,
    Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition,
    Symfony\Component\DependencyInjection\ContainerBuilder,
    Symfony\Component\DependencyInjection\Loader\XmlFileLoader;

use Behat\Testwork\ServiceContainer\Extension as ExtensionInterface,
    Behat\Testwork\ServiceContainer\ExtensionManager;

/**
 * Mink extension for MailCatcher manipulation.
 *
 * @author Alexandre Salomé <alexandre.salome@gmail.com>
 */
class Extension implements ExtensionInterface
{
    /**
     * {@inheritdoc}
     */
    public function getConfigKey()
    {
        return 'alex_mailcatcher';
    }

    /**
     * {@inheritdoc}
     */
    public function initialize(ExtensionManager $extensionManager)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function configure(ArrayNodeDefinition $builder)
    {
        $builder
            ->children()
                ->booleanNode('purge_before_scenario')->defaultTrue()->end()
                ->scalarNode('url')->defaultValue('http://localhost:1080')->end()
            ->end()
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
    }

    /**
     * {@inheritdoc}
     */
    public function load(ContainerBuilder $container, array $config)
    {
        $loader = new XmlFileLoader($container, new FileLocator(__DIR__.'/services'));
        $loader->load('core.xml');

        $container->setParameter('behat.mailcatcher.client.url', $config['url']);
        $container->setParameter('behat.mailcatcher.purge_before_scenario', $config['purge_before_scenario']);
    }

    /**
     * {@inheritdoc}
     */
    public function getCompilerPasses()
    {
        return array();
    }

    protected function loadEnvironmentConfiguration()
    {
        $config = array();

        if ($url = getenv('MAILCATCHER_URL')) {
            $config['url'] = $url;
        }

        return $config;
    }
}
