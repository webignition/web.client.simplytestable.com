<?php
namespace SimplyTestable\WebClientBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;

class FlashBagValues
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @param Session $session
     */
    public function __construct(Session $session)
    {
        $this->session = $session;
    }

    /**
     * @param string[] $keys
     *
     * @return array
     */
    public function get($keys)
    {
        $values = [];

        $flashBag = $this->session->getFlashBag();

        foreach ($keys as $key) {
            $flashValues = $flashBag->get($key);

            if (!empty($flashValues)) {
                $values[$key] = $flashValues[0];
            }
        }

        return $values;
    }

    /**
     * @param string $key
     *
     * @return mixed|null
     */
    public function getSingle($key)
    {
        $values = $this->get([$key]);

        return isset($values[$key])
            ? $values[$key]
            : null;
    }
}
