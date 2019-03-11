<?php

namespace App\Controller\View\Test\Results;

use App\Controller\AbstractBaseViewController;
use App\Entity\Test;
use App\Model\RemoteTest\RemoteTest;

abstract class AbstractResultsController extends AbstractBaseViewController
{
    const RESULTS_PREPARATION_THRESHOLD = 100;

    /**
     * @param RemoteTest $remoteTest
     * @param Test $test
     *
     * @return bool
     */
    protected function requiresPreparation(RemoteTest $remoteTest, Test $test)
    {
        return ($remoteTest->getTaskCount() - self::RESULTS_PREPARATION_THRESHOLD) > $test->getTaskCount();
    }
}
