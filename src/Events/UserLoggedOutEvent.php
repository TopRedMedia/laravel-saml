<?php

namespace TopRedMedia\SAML\Events;

use OneLogin\Saml2\Auth;
use TopRedMedia\SAML\SAMLUser;
use TopRedMedia\SAML\Traits\LoadISPSettingsTrait;

class UserLoggedOutEvent
{
    use LoadISPSettingsTrait;

    //public $user;
    public $isp;

    public function __construct($isp)
    {
        //$this->user = $user;
        $this->isp = $isp;
    }

    /**
     * Get a OneLogin\Saml2\Auth instance
     *
     * @return Auth
     * @throws \OneLogin\Saml2\Error
     * @throws \TopRedMedia\SAML\Exceptions\ISPSettingsNotFoundException
     */
    public function getAuth()
    {
        $settings = $this->getSettings();
        return new Auth($settings);
    }
}
