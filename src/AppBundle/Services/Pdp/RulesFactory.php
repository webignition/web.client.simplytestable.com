<?php

namespace AppBundle\Services\Pdp;

use Pdp\Rules;

class RulesFactory
{
    /**
     * @var string
     */
    private $pspPslDataPath;

    /**
     * @param string $pspPslDataPath
     */
    public function __construct($pspPslDataPath)
    {
        $this->pspPslDataPath = $pspPslDataPath;
    }

    /**
     * @return Rules
     */
    public function create()
    {
        $rulesArray = json_decode(file_get_contents($this->pspPslDataPath), true);

        return new Rules($rulesArray);
    }
}
