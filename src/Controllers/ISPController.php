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
     * @param $isp
     * @throws ISPSettingsNotFoundException
     * @throws \OneLogin\Saml2\Error
     */
    public function info($isp)
    {
        $auth = new Auth($isp);;
        // TODO
        dump($auth);
    }

    /**
     * Login callback for {prefix}/{isp}/login
     *
     * @param $isp
     * @throws \OneLogin\Saml2\Error
     * @throws ISPSettingsNotFoundException
     */
    public function login($isp)
    {
        $auth = new Auth($isp);;
        $auth->login(config('saml2_settings.loginRoute'));
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
        $auth->logout($returnTo, $nameId, $sessionIndex); //will actually end up in the sls endpoint
        //does not return
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
            Session::flash('saml2_error_detail', [$auth->getLastErrorReason()]);
            Log::error('SAML error', $errors);
            Session::flash('saml2_error', $errors);
            // TODO
            return redirect(config('saml2_settings.errorRoute'));
        }

        // Here we have a user in $this->auth;
        $user = $auth->getSAMLUser();
        event(new UserLoggedInEvent($user));
        $redirectUrl = $user->getIntendedUrl();
        if ($redirectUrl !== null) {
            return redirect($redirectUrl);
        } else {
            // TODO
            return redirect(config('saml2_settings.loginRoute'));
        }
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
        $auth = new Auth($isp);;
        $error = $auth->sls(config('saml2_settings.retrieveParametersFromServer'));
        if (!empty($error)) {
            throw new \Exception("Could not log out");
        }
        return redirect(config('saml2_settings.logoutRoute')); //may be set a configurable default
    }
}
