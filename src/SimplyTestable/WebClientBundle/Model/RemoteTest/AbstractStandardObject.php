<?php
namespace SimplyTestable\WebClientBundle\Model\RemoteTest;

class AbstractStandardObject
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
     * @return array
     */
    public function getSource()
    {
        return $this->source;
    }

    /**
     * @return array
     */
    public function getArraySource()
    {
        return $this->source;
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
