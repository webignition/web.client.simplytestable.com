<?php

namespace SimplyTestable\WebClientBundle\Model;

abstract class AbstractArrayBasedModel
{
    /**
     * @var array
     */
    protected $source;

    /**
     * @param array $source
     */
    public function __construct(array $source)
    {
        $this->source = $source;
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    protected function getProperty($name)
    {
        if (!$this->hasProperty($name)) {
            return null;
        }

        return $this->source[$name];
    }

    /**
     * @param string $name
     *
     * @return bool
     */
    protected function hasProperty($name)
    {
        return array_key_exists($name, $this->source);
    }
}
