<?php
namespace Unculture\Scraper;

use DOMDocument;
use DOMXPath;
use stdClass;
use RuntimeException;

/**
 * Class ScrapeCommand
 * @package Unculture\Scraper
 */
class ScrapeCommand
{
    /**
     * A front controller for this bespoke web scraper
     * @param $url The URL of the product list page to scrape products from
     */
    public function execute($url)
    {
        $retriever = new HtmlRetriever();
        $html = $retriever->retrieveHtml($url);

        try {
            $xpath = $this->createDOMXPathObjectFromHtml($html);
        } catch(RuntimeException $e) {
            print $e->getMessage();
            die;
        }

        $listPageParser = new ProductListPageParser($xpath);
        $products = $listPageParser->getProducts();

        foreach ($products as $product) {
            // NB: This I/O can be made non-blocking in PHP, but no need to do so here
            $html = $retriever->retrieveHtml($product->link);

            // Here I'm just taking the HTML size to mean the length of the html string in Kb
            // mb_strln with encoding set to '8bit' gives the byte length back as an integer
            $product->size = sprintf("%0.1fKb", mb_strlen($html, '8bit') / 1024);
            try {
                $xpath = $this->createDOMXPathObjectFromHtml($html);
            } catch(RuntimeException $e) {
                print $e->getMessage();
                die;
            }
            $singlePageParser = new SingleProductPageParser($xpath);
            $product->description = $singlePageParser->getDescription();
            unset($product->link);
        }

        $output = new stdClass();
        $output->results = $products;

        $output->total = $this->calculateTotal($output->results);


        echo json_encode($output, JSON_PRETTY_PRINT);

    }

    /**
     * Builds the DOMXPath element needed to extract data from the HTML
     *
     * @throws RuntimeException
     * @param $html
     * @return DOMXPath
     */
    private function createDOMXPathObjectFromHtml($html)
    {
        libxml_use_internal_errors(true);
        $doc = new DOMDocument();
        $doc->encoding = "windows-1252";
        if (!$doc->loadHTML($html)) {
            throw new RuntimeException("Parsing HTML has failed - perhaps there was a network error, or a bad URL passed.");
        }
        libxml_clear_errors();
        return new DOMXpath($doc);
    }

    /**
     * Iterates through the products and returns the total of all "unit_price"s
     *
     * @param $products array The array of product objects
     * @return float
     */
    private function calculateTotal($products)
    {
        $total = 0.0;
        foreach ($products as $product) {
            $total += $product->unit_price;
        }
        return $total;
    }
}
