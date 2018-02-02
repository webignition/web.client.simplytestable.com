<?php
namespace SimplyTestable\WebClientBundle\Services;

use Guzzle\Http\Message\Request;
use SimplyTestable\WebClientBundle\Model\User;

abstract class CoreApplicationService
{
    /**
     * @var User;
     */
    private static $user;

    /**
     * @var WebResourceService
     */
    protected $webResourceService;

    /**
     * @param WebResourceService $webResourceService
     */
    public function __construct(WebResourceService $webResourceService)
    {
        $this->webResourceService = $webResourceService;
    }

    /**
     * @param User $user
     */
    public function setUser(User $user)
    {
        self::$user = $user;
    }

    /**
     * @return User
     */
    public function getUser()
    {
        return self::$user;
    }

    /**
     * @return bool
     */
    public function hasUser()
    {
        return !is_null($this->getUser());
    }

    /**
     * @param Request $request
     *
     * @return Request
     */
    protected function addAuthorisationToRequest(Request $request)
    {
        $user = $this->getUser();

        $request->addHeaders(array(
            'Authorization' => 'Basic ' . base64_encode($user->getUsername().':'.$user->getPassword())
        ));

        return $request;
    }
}
