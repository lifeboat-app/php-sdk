<?php

namespace Examples\Controllers;

/**
 * Class ExampleController
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
 * @see ExampleController::process()
 * This controller action shows how to handle the response from the Lifeboat Auth,
 * using the Lifeboat SDK
 *
 * /auth/error
 * @see ExampleController::error()
 * This controller action shows how to handle Lifeboat Auth errors
 *
 * /auth/change
 * @see ExampleController::change()
 * This controller action shows how to handle user requests to change between sites he/she
 * has access to
 *
 * /auth/select
 * @see ExampleController::select()
 * This controller action shows what how to present a site selection screen the user
 * has access to
 */
class ExampleController extends Controller {

    const LIFEBOAT_APP_ID       = '[[Lifeboat App ID]]';
    const LIFEBOAT_APP_SECRET   = '[[Lifeboat App Secret]]';

    private static $url_segment     = 'auth';
    private static $allowed_actions = ['process', 'error', 'select', 'change'];

    /** @var \Lifeboat\App $app */
    private $app;

    public function __construct()
    {
        parent::__construct();
        $this->app = new \Lifeboat\App(self::LIFEBOAT_APP_ID, self::LIFEBOAT_APP_SECRET);
    }

    /**
     * Process the code returns by the Lifeboat Auth process
     * and ensure the user has selected an active site
     *
     * @return HTTPResponse
     */
    public function process(): HTTPResponse
    {
        // This function will automatically create an access token
        // and save it into $_SESSIONS
        $this->app->fetchAccessToken($_GET['code'] ?? '');

        // Check if the user has selected a Lifeboat site to use.
        // If not redirect them so they can select
        if (!$this->app->getActiveSite()) {
            return $this->redirect($this->Link('select'));
        }

        return $this->redirect('/');
    }

    /**
     * This function handles the switching between Lifeboat sites
     * the user has access to
     *
     * Example: https://app.com/auth/change?site_key=[lifeboat_site_key]
     *
     * @return HTTPResponse
     */
    public function change(): HTTPResponse
    {
        // The site of the Lifeboat site we want to be set as active
        $key = $_GET['site_key'];

        try {
            foreach ($this->app->getSites() as $site) {
                if ($site['site_key'] === $key) {
                    // This function automatically saves the user preference to $_SESSION
                    $this->app->setActiveSite($site['domain'], $site['site_key']);
                    break;
                }
            }
        } catch (\Lifeboat\Exceptions\OAuthException $e) {
            // If we encounter any auth issues, restart the auth process
            return $this->reloadAuth();
        }

        // If everything went well redirect to the app's index page
        return $this->redirect('/');
    }

    /**
     * @see ExampleController::select()
     * @return HTTPResponse
     */
    public function select()
    {
        try {
            $sites = $this->app->getSites();

            // If the user doesn't have access to any Lifeboat site force a re-auth
            if (count($sites) < 1) return $this->reloadAuth();

            // If the user only has access to one Lifeboat site then automatically use that
            if (count($sites) === 1) {
                $this->app->setActiveSite($sites[0]['domain'], $sites[0]['site_key']);
                return $this->redirect('/');
            }


            // Create a list of available Lifeboat sites with links
            // to switch the active site
            $options = [];
            foreach ($sites as $site) {
                $options[] = [
                    'Label' => $site['domain'],
                    'URL'   => $this->Link('select') . '?site_key=' . $site['site_key']
                ];
            }

            // Render this page showing a list of all available Lifeboat sites
            // the current user has access to so that he/she can switch
            // between them
            return $this->renderWith('[[Your template]]', [
               'Options' => $options
            ]);


            return $this;
        } catch (\Lifeboat\Exceptions\OAuthException $e) {
            return $this->reloadAuth();
        }
    }

    /**
     * @return HTTPResponse
     */
    public function reloadAuth(): HTTPResponse
    {
        // URL to process the auth response
        $process = $this->Link('process');

        // URL to handle auth errors
        $error = $this->Link('error');

        // A one-time use challenge code to prevent man in the middle attacks
        $challenge = $this->app->getAPIChallenge();

        // Redirect to the auth URL
        return $this->redirect($this->app->getAuthURL($process, $error, $challenge));
    }

    /**
     * @return string
     */
    public function getSiteName(): string
    {
        $site = $this->app->getActiveSite();
        return ($site) ? $site['host'] : '';
    }
}
