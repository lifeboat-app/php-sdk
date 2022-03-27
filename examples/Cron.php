<?php

namespace Examples\Controllers;

use Examples\Models\Store;
use Lifeboat\App;
use Lifeboat\Exceptions\ApiException;
use Lifeboat\Exceptions\OAuthException;
use Lifeboat\Models\Product;
use Lifeboat\Resource\ListResource;

/**
 * Class Cron
 * @package Examples\Controllers
 *
 * An example controller showcasing how you may use the Lifeboat SDK
 * when a user is unable to interact with your app
 */
class Cron extends Controller {

    const LIFEBOAT_APP_ID       = '[[Lifeboat App ID]]';
    const LIFEBOAT_APP_SECRET   = '[[Lifeboat App Secret]]';

    /**
     * In this function we demonstrate fetching products
     * without an action session.
     *
     * Note: We're passing the Store object that contains $SiteKey & $Host
     * These parameters are required when making request without an active session
     *
     * Note: The SDK will automatically request, refresh and cache access token.
     * You don't need to call the @see App::fetchAccessToken()
     *
     * @see Auth::process() - To see how we're creating and setting the Store object
     * @see Store::find_or_make() - To see how we would retreive a store
     *
     * @param Store $store
     * @return ListResource
     */
    public static function fetch_products(Store $store): ListResource
    {
        // Initialise the SDK
        $app = new App(self::LIFEBOAT_APP_ID, self::LIFEBOAT_APP_SECRET);

        // Set the site we'll be interacting with
        $app->setActiveSite($store->getSiteHost(), $store->getSiteKey());

        // Get an iterator for all the products in $store
        return $app->products->all();
    }

    /**
     * In this example we'll show to set a fixed price to all the products
     * in a $store without an active session
     *
     * @param Store $store
     * @param float $price
     * @return void
     */
    public static function set_new_price(Store $store, float $price)
    {
        // Initialise the SDK
        $app = new App(self::LIFEBOAT_APP_ID, self::LIFEBOAT_APP_SECRET);

        // Set the site we'll be interacting with
        $app->setActiveSite($store->getSiteHost(), $store->getSiteKey());

        /** @var Product $product */
        foreach ($app->products->all() as $product) {
            try {
                $app->products->update($product->ID, [
                    'Price' => $price
                ]);
            } catch (OAuthException $auth) {
                // Will be thrown if your app is not authorised to access this store
                error_log($auth);
            } catch (ApiException $api) {
                // Will be thrown if you've provided invalid data to the API
                error_log($api);
            }
        }
    }
}
