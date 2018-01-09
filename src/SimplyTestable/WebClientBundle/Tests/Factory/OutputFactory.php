<?php

namespace SimplyTestable\WebClientBundle\Tests\Factory;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\Task\Output;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OutputFactory
{
    const KEY_TYPE = 'type';

    const DEFAULT_TYPE = Output::TYPE_HTML_VALIDATION;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $defaultOutputValues = [
        self::KEY_TYPE => self::DEFAULT_TYPE,
    ];

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    /**
     * @param array $outputValues
     *
     * @return Output
     */
    public function create(array $outputValues)
    {
        /* @var EntityManagerInterface $entityManager */
        $entityManager = $this->container->get('doctrine.orm.entity_manager');

        foreach ($this->defaultOutputValues as $key => $value) {
            if (!isset($outputValues[$key])) {
                $outputValues[$key] = $value;
            }
        }

        $output = new Output();

        $output->setType($outputValues[self::KEY_TYPE]);

        $entityManager->persist($output);
        $entityManager->flush();

        return $output;
    }
}
