<?php

use Unculture\Scraper\SingleProductPageParser;

class SingleProductPageParserTest extends PHPUnit_Framework_TestCase
{
    public $type_one_product_document_xpath;
    public $type_two_product_document_xpath;

    public function setUp()
    {
        libxml_use_internal_errors(true);

        $doc = new DOMDocument();
        $doc->encoding = "windows-1252";
        $doc->loadHTMLFile(__DIR__ . DIRECTORY_SEPARATOR . "resources/type_one_product_page.html");
        $this->type_one_product_document_xpath = new DOMXpath($doc);

        $doc = new DOMDocument();
        $doc->encoding = "windows-1252";
        $doc->loadHTMLFile(__DIR__ . DIRECTORY_SEPARATOR . "resources/type_two_product_page.html");
        $this->type_two_product_document_xpath = new DOMXpath($doc);

        libxml_clear_errors();
    }

    public function testGetDescriptionForPageTypeOne()
    {
        $p = new SingleProductPageParser($this->type_one_product_document_xpath);
        $d = $p->getDescription();
        $this->assertEquals("Avocados", $d);
    }

    public function testGetDescriptionForPageTypeTwo()
    {
        $p = new SingleProductPageParser($this->type_two_product_document_xpath);
        $d = $p->getDescription();
        $this->assertEquals("Extra Large Avocado Ripe & ready", $d);
    }
}
