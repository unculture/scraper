<?php

namespace Unculture\Scraper;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;
use Guzzle\Plugin\Cookie\CookiePlugin;
use Guzzle\Plugin\Cookie\CookieJar\ArrayCookieJar;

/**
 * Class HtmlRetriever
 *
 * @package Unculture\Scraper
 */
class HtmlRetriever
{
    /**
     * Retrieves HTML (more specifically, the body of the HTTP response) from the given URL
     *
     * @param $url string The URL from which you wish to retrieve HTML
     * @return mixed
     */
    public function retrieveHtml($url)
    {
        $client = $this->buildClient();
        $request = $this->buildRequest($client, $url);
        $response = $this->sendRequest($request);

        if ($response->getStatusCode() !== 200) {
            throw new \UnexpectedValueException("Did not get a 200 OK response for the URL \"$url \"");
        }
        return $response->getBody();
    }

    /**
     * @return Client
     */
    private function buildClient()
    {
        $cookiePlugin = new CookiePlugin(new ArrayCookieJar());
        $client = new Client();
        $client->addSubscriber($cookiePlugin);
        return $client;
    }

    /**
     * @param $client
     * @param $url
     * @return mixed
     */
    private function buildRequest($client, $url)
    {
        return $client->get($url);
    }

    /**
     * @param  $request
     * @return mixed
     */
    private function sendRequest($request)
    {
        return $request->send();
    }

}