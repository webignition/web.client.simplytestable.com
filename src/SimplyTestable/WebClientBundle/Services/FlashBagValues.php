<?php
namespace SimplyTestable\WebClientBundle\Services;

use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class FlashBagValues
{
    /**
     * @var Session
     */
    private $session;

    /**
     * @param SessionInterface $session
     */
    public function __construct(SessionInterface $session)
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
                $isSingleItemArray = 1 === count($flashValues);
                $isSingleItemNumericallyIndexedArray = $isSingleItemArray && [0] === array_keys($flashValues);

                if ($isSingleItemNumericallyIndexedArray) {
                    $values[$key] = $flashValues[0];
                } else {
                    $values[$key] = $flashValues;
                }
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
