<?php

use Unculture\Scraper\ProductListPageParser;

class ProductListPageParserTest extends PHPUnit_Framework_TestCase
{
    public $product_list_document_xpath;
    public $single_product_document_xpath;

    public function setUp()
    {
        libxml_use_internal_errors(true);

        $doc = new DOMDocument();
        $doc->encoding = "windows-1252";
        $doc->loadHTMLFile(__DIR__ . DIRECTORY_SEPARATOR . "resources/product_list_page.html");
        $this->product_list_document_xpath = new DOMXpath($doc);

        $doc = new DOMDocument();
        $doc->encoding = "windows-1252";
        $doc->loadHTMLFile(__DIR__ . DIRECTORY_SEPARATOR . "resources/single_product_in_list.html");
        $this->single_product_document_xpath = new DOMXpath($doc);

        libxml_clear_errors();
    }

    public function testCorrectNumberOfProductsFound()
    {
        $p = new ProductListPageParser($this->product_list_document_xpath);
        $products = $p->getProducts();
        var_dump(json_encode($p->getProducts(), JSON_PRETTY_PRINT));
        $this->assertEquals(12, count($products));
    }

    public function testProductIsExtractedCorrectly()
    {
        $p = new ProductListPageParser($this->single_product_document_xpath);
        $product = $p->getProducts()[0];
        $this->assertEquals("http://www.sainsburys.co.uk/shop/gb/groceries/ripe---ready/sainsburys-avocado-xl-pinkerton-loose-300g", $product->link);
        $this->assertEquals("Sainsbury's Avocado Ripe & Ready XL Loose 300g", $product->title);
        $this->assertEquals(1.5, $product->unit_price);
    }


}