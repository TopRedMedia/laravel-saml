<?php

namespace TopRedMedia\SAML\Events;

use TopRedMedia\SAML\SAMLUser;
use TopRedMedia\SAML\Traits\LoadISPSettingsTrait;

class UserLoggedInEvent
{
    use LoadISPSettingsTrait;

    public $user;

    public function __construct(SAMLUser $user)
    {
        $this->user = $user;
    }
}
