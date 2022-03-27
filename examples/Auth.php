<?php

namespace Examples\Controllers;

use Examples\Models\Store;
use Lifeboat\App;
use Lifeboat\Exceptions\OAuthException;

/**
 * Class Auth
 * @package Examples\Controllers
 *
 * An example controller as how to handle the Lifeboat Auth process
 *
 * BEFORE YOU START
 * You will need to register your app with Lifeboat team.
 * Contact hello@lifeboat.app to get your app credentials.
 *
 * This controller is designed to show how your app will authenticate with Lifeboat APIs
 * and allow your app to interact with Lifeboat APIs where the logged in user permissions
 * are automatically checked at an API level.
 *
 * Base url: /auth
 *
 * /auth/process
 * @see Auth::process()
 * This controller action shows how to handle the response from the Lifeboat Auth,
 * using the Lifeboat SDK
 *
 * /auth/error
 * @see Auth::error()
 * This controller action shows how to handle Lifeboat Auth errors
 */
class Auth extends Controller {

    const LIFEBOAT_APP_ID       = '[[Lifeboat App ID]]';
    const LIFEBOAT_APP_SECRET   = '[[Lifeboat App Secret]]';

    private static $url_segment     = 'auth';
    private static $allowed_actions = ['process', 'error'];

    /** @var \Lifeboat\App $app */
    private static $_app;

    /**
     * Process the code returns by the Lifeboat Auth process
     * and ensure the user has selected an active site
     */
    public function process()
    {
        // It's essential for the app to run correctly that sessions
        // are started and working
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        // This function will automatically create an access token
        // and save it into $_SESSIONS
        try {
            self::get_app()->fetchAccessToken($_GET['code'] ?? '');
        } catch (OAuthException $e) {
            error_log($e);
            $this->reloadAuth();
        }

        /**
         * OPTIONAL:
         * If you need to perform actions off-session (cron, etc...)
         * You'll need to store the host and site_key
         * @see Store::find_or_make()
         * @see App::setActiveSite()
         * @see App::getAccessToken()
         */
        Store::find_or_make(self::get_app()->getSiteKey(), self::get_app()->getHost());

        header("Location: /");
        flush();
        die();
    }

    public function reloadAuth()
    {
        // URL to process the auth response
        $process = '/auth/process';

        // URL to handle auth errors
        $error = '/auth/error';

        // A one-time use challenge code to prevent man in the middle attacks
        $challenge = self::get_app()->getAPIChallenge();

        // Redirect to the auth URL
        header("Location: " . self::get_app()->getAuthURL($process, $error, $challenge));
        flush();
        die();
    }


    /**
     * @return App
     */
    public static function get_app(): App
    {
        if (!self::$_app) {
            self::$_app = new App(self::LIFEBOAT_APP_ID, self::LIFEBOAT_APP_SECRET);
        }

        return self::$_app;
    }
}
