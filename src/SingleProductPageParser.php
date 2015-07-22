<?php

namespace Unculture\Scraper;

use \DOMXPath;

/**
 * Class SingleProductPageParser
 * @package Unculture\Scraper
 */
class SingleProductPageParser
{
    /**
     * @var DOMXPath
     */
    private $xpath;

    /**
     * @param DOMXPath $xpath A DOMXPath object containing the document with the product description in it
     */
    public function __construct(DOMXPath $xpath)
    {
        $this->xpath = $xpath;
    }

    /**
     * Gets the product description from the DOMXPath object provided at instantiation
     * @return string The product description
     */
    public function getDescription()
    {
        // NB. Product pages can have at least two types of markup

        // Some items have embedded XML with the product description
        $productContentItem = $this->xpath->query('//productcontent/htmlcontent')->item(0);
        if ($productContentItem) {
            return $this->getDescriptionFromMarkupTypeOne($productContentItem);
        }

        // Other items have a h3 element containing the string "Description", then actual
        // description text contained in subsequent sibling nodes
        return $this->getDescriptionFromMarkupTypeTwo();
    }

    /**
     * @param $productContentItem
     * @return string The extracted description
     */
    private function getDescriptionFromMarkupTypeOne($productContentItem)
    {
        $description = "";
        foreach ($productContentItem->childNodes as $element) {
            if (trim($element->nodeValue) == "Description") {
                while ($element = $element->nextSibling) {
                    if (property_exists($element, "tagName") && strtolower($element->tagName) == "h3") {
                        break;
                    }
                    $description .= trim($element->nodeValue) . " ";
                }
                break;
            }
        }
        return trim($description);
    }

    /**
     * @return string The extracted description
     */
    private function getDescriptionFromMarkupTypeTwo()
    {
        $description = "";
        $h3Elements = $this->xpath->query('//h3');
        foreach ($h3Elements as $element) {
            if (trim($element->nodeValue) == "Description") {
                while ($element = $element->nextSibling) {
                    if (!empty(trim($element->nodeValue))) {
                        $description .= trim($element->nodeValue) . " ";
                    }
                }
                break;
            }
        }
        return trim($description);
    }


}