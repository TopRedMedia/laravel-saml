<?php

namespace TopRedMedia\SAML\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use TopRedMedia\SAML\Auth;
use TopRedMedia\SAML\Events\UserLoggedInEvent;
use TopRedMedia\SAML\Exceptions\ISPSettingsNotFoundException;

class ISPController extends Controller
{
    /**
     * Login callback for {prefix}/{isp}/login
     *
     * @param $isp
     * @throws \OneLogin\Saml2\Error
     * @throws ISPSettingsNotFoundException
     */
    public function login($isp)
    {
        $auth = new Auth($isp);
        $settings = $auth->getSettings();

        $auth->login($settings['retrieveParametersFromServer']);
    }

    /**
     * Logout callback for {prefix}/{isp}/logout
     *
     * @param Request $request
     * @param $isp
     * @throws \OneLogin\Saml2\Error
     * @throws ISPSettingsNotFoundException
     */
    public function logout(Request $request, $isp)
    {
        $auth = new Auth($isp);;
        $returnTo = $request->query('returnTo');
        $sessionIndex = $request->query('sessionIndex');
        $nameId = $request->query('nameId');
        $auth->logout($returnTo, $nameId, $sessionIndex);
    }

    /**
     * Metadata callback for {prefix}/{isp}/metadata
     *
     * @param $isp
     * @return \Illuminate\Contracts\Routing\ResponseFactory|\Illuminate\Http\Response
     * @throws \OneLogin\Saml2\Error
     * @throws ISPSettingsNotFoundException
     */
    public function metadata($isp)
    {
        $auth = new Auth($isp);;
        $metadata = $auth->getMetadata();
        return response($metadata, 200, ['Content-Type' => 'text/xml']);
    }

    /**
     * ACS callback for {prefix}/{isp}/acs
     *
     * @param $isp
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws ISPSettingsNotFoundException
     * @throws \OneLogin\Saml2\Error
     */
    public function acs($isp)
    {
        $auth = new Auth($isp);;
        $errors = $auth->acs();
        if (!empty($errors)) {
            Log::error('SAML error_detail', ['error' => $auth->getLastErrorReason()]);
            Session::put('saml_error_detail', [$auth->getLastErrorReason()]);

            Log::error('SAML error', $errors);
            Session::put('saml_error', $errors);

            $settings = $auth->getSettings();
            return redirect($settings['errorRoute']);
        }

        // Here we have a user in $this->auth
        $user = $auth->getSAMLUser();

        // There has to be at least one listener that handles the login event. No further output will be generated.
        event(new UserLoggedInEvent($user));
    }

    /**
     * SLS callback for {prefix}/{isp}/sls
     *
     * @param $isp
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     * @throws \Exception
     */
    public function sls($isp)
    {
        $auth = new Auth($isp);
        $settings = $auth->getSettings();

        $error = $auth->sls($settings['retrieveParametersFromServer']);
        if (!empty($error)) {
            throw new \Exception("Could not log out");
        }

        return redirect($settings['logoutRoute']);
    }
}
