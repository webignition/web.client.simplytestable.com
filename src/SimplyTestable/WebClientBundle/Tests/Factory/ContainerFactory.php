<?php

namespace SimplyTestable\WebClientBundle\Tests\Factory;

use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\DependencyInjection\ContainerInterface;

class ContainerFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $serviceIds
     * @param array $services
     * @param array $parameterIds
     *
     * @return ContainerInterface
     */
    public function create(array $serviceIds, array $services, array $parameterIds = [])
    {
        $container = new Container();

        foreach ($serviceIds as $serviceId) {
            $container->set($serviceId, $this->container->get($serviceId));
        }

        foreach ($services as $serviceId => $service) {
            $container->set($serviceId, $service);
        }

        foreach ($parameterIds as $parameterId) {
            $container->setParameter($parameterId, $this->container->getParameter($parameterId));
        }

        return $container;
    }
}
