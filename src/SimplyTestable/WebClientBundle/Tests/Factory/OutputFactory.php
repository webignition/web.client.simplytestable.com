<?php

namespace SimplyTestable\WebClientBundle\Tests\Factory;

use Doctrine\ORM\EntityManagerInterface;
use SimplyTestable\WebClientBundle\Entity\Task\Output;
use Symfony\Component\DependencyInjection\ContainerInterface;

class OutputFactory
{
    const KEY_TYPE = 'type';
    const KEY_ERROR_COUNT = 'error-count';
    const KEY_WARNING_COUNT = 'warning-count';

    const DEFAULT_TYPE = Output::TYPE_HTML_VALIDATION;
    const DEFAULT_ERROR_COUNT = 0;
    const DEFAULT_WARNING_COUNT = 0;

    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var array
     */
    private $defaultOutputValues = [
        self::KEY_TYPE => self::DEFAULT_TYPE,
        self::KEY_ERROR_COUNT => self::DEFAULT_ERROR_COUNT,
        self::KEY_WARNING_COUNT => self::DEFAULT_WARNING_COUNT,
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
        $output->setErrorCount($outputValues[self::KEY_ERROR_COUNT]);
        $output->setWarningCount($outputValues[self::KEY_WARNING_COUNT]);

        $entityManager->persist($output);
        $entityManager->flush();

        return $output;
    }
}
