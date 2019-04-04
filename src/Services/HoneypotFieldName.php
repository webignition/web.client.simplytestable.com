<?php

namespace App\Services;

use Symfony\Component\HttpFoundation\Session\SessionInterface;

class HoneypotFieldName
{
    const SESSION_KEY = 'honeypot_field_name';

    private $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;

        if (empty($this->session->get(self::SESSION_KEY))) {
            $this->session->set(self::SESSION_KEY, md5(rand()));
        }
    }

    public function get()
    {
        return $this->session->get(self::SESSION_KEY);
    }
}
