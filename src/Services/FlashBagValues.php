<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;

class FlashBagValues
{
    /**
     * @var FlashBagInterface
     */
    private $flashBag;

    public function __construct(FlashBagInterface $flashBag)
    {
        $this->flashBag = $flashBag;
    }

    /**
     * @param string[] $keys
     *
     * @return array
     */
    public function get($keys)
    {
        $values = [];

        foreach ($keys as $key) {
            $flashValues = $this->flashBag->get($key);

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
