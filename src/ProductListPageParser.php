<?php

namespace Unculture\Scraper;

use \DOMXPath;
use \stdClass;

/**
 * Class ProductListPageParser
 * @package Unculture\Scraper
 */
class ProductListPageParser
{
    /**
     * @var DOMXPath
     */
    private $xpath;

    /**
     * @param DOMXPath $xpath A DOMXPath object containing the document with the list of products in it
     */
    public function __construct(DOMXPath $xpath)
    {
        $this->xpath = $xpath;
    }

    /**
     * Uses the DOMXpath object passed in the constructor to extract an
     * array of products.
     * Products are stdClass objects with title, link and unit_price properties
     *
     * @return array
     */
    public function getProducts()
    {
        $products = [];
        $productFragments = $this->xpath->query('//div[@class="productInner"]');

        foreach($productFragments as $product) {
            $title = trim($this->xpath->query('.//h3/a/text()', $product)->item(0)->nodeValue);
            $link = $this->xpath->query('.//h3/a', $product)->item(0)->getAttribute("href");
            $unitPrice = (float) preg_replace('/[^\d.]/', "", trim($this->xpath->query('.//p[@class="pricePerUnit"]/text()', $product)->item(0)->nodeValue));

            $result = new stdClass();
            $result->title = $title;
            $result->link = $link;
            $result->unit_price = $unitPrice;

            $products[] = $result;
        }

        return $products;

    }

    /**
     * @param $products
     * @return int
     */
    public function calculateGrandTotal($products)
    {
        $total = 0;
        foreach($products as $product) {
            $total += $product->unit_price;
        }
        return $total;
    }
}