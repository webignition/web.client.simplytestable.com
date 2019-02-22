<?php

namespace App\Event\Stripe;

use Symfony\Component\EventDispatcher\Event as BaseEvent;

class Event extends BaseEvent
{

    const NAME_KEY = 'event';
    const USER_KEY = 'user';


    /**
     *
     * @var \Symfony\Component\HttpFoundation\ParameterBag
     */
    private $data;



    /**
     *
     * @param \Symfony\Component\HttpFoundation\ParameterBag $data
     */
    public function __construct(\Symfony\Component\HttpFoundation\ParameterBag $data)
    {
        $this->data = $data;
    }


    /**
     *
     * @return \Symfony\Component\HttpFoundation\ParameterBag
     */
    public function getData()
    {
        return $this->data;
    }


    /**
     *
     * @return string|null
     */
    public function getName()
    {
        return $this->data->get(self::NAME_KEY);
    }


    /**
     *
     * @return string
     */
    public function getUser()
    {
        return $this->data->get(self::USER_KEY);
    }


    /**
     *
     * @return boolean
     */
    public function hasUser()
    {
        return !is_null($this->getUser());
    }
}
