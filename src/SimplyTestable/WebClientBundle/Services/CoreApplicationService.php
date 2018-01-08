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
     * @var array
     */
    private $parameters;

    /**
     * @param array $parameters
     * @param WebResourceService $webResourceService
     */
    public function __construct(
        $parameters,
        WebResourceService $webResourceService
    ) {
        $this->parameters = $parameters;
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
     * @param string $name
     * @param array $parameters
     *
     * @return string
     */
    protected function getUrl($name = null, $parameters = null)
    {
        $url = $this->parameters['urls']['base'];

        if (!is_null($name)) {
            $url .= $this->parameters['urls'][$name];
        }

        if (is_array($parameters)) {
            foreach ($parameters as $parameterName => $parameterValue) {
                $url = str_replace('{'.$parameterName.'}', $parameterValue, $url);
            }
        }

        return $url;
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
