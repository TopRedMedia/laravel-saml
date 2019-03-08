<?php

namespace TopRedMedia\SAML\Traits;

use TopRedMedia\SAML\Exceptions\ISPSettingsNotFoundException;

trait LoadISPSettingsTrait
{
    /**
     * Correctly retrieve the settings (respect default settings, urls and overridden ones for a specific isp).
     *
     * @return array
     * @throws ISPSettingsNotFoundException
     */
    public function getSettings()
    {
        $settings_default = config('topredmedia-saml.default');
        $settings_isp = config('topredmedia-saml.endpoints.' . $this->isp, null);
        if (is_null($settings_isp)) {
            throw new ISPSettingsNotFoundException('No settings for endpoint ' . $this->isp);
        }
        $settings = array_merge($settings_default, $settings_isp);

        // Alter the settings to always use default routes for this package
        $saml_base_url = config('app.url', '') . config('topredmedia-saml.route_prefix', '') . '/' . $this->isp . '/';

        $settings['sp']['assertionConsumerService']['url'] = $saml_base_url . 'acs';
        $settings['sp']['singleLogoutService']['url'] = $saml_base_url . 'sls';
        $settings['sp']['entityId'] = $saml_base_url.'metadata';

        return $settings;
    }
}
