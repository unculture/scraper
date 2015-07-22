<?php

use Unculture\Scraper\HtmlRetriever;

class HtmlRetrieverTest extends PHPUnit_Framework_TestCase
{
    public function testInitialPageReturnsWithoutThrowing()
    {
        $retriever = new HtmlRetriever();

        $url = "http://www.sainsburys.co.uk/webapp/wcs/stores/servlet/CategoryDisplay?listView=true&orderBy=FAVOURITES_FIRST&parent_category_rn=12518&top_category=12518&langId=44&beginIndex=0&pageSize=20&catalogId=10137&searchTerm=&categoryId=185749&listId=&storeId=10151&promotionId=#langId=44&storeId=10151&catalogId=10137&categoryId=185749&parent_category_rn=12518&top_category=12518&pageSize=20&orderBy=FAVOURITES_FIRST&searchTerm=&beginIndex=0&hideFilters=true";

        $retriever->retrieveHtml($url);
    }

    /**
     * @expectedException   Guzzle\Http\Exception\ClientErrorResponseException
     */
    public function testGettingA404ThrowsUnexpectedValueException()
    {
        $retriever = new HtmlRetriever();

        $url = "http://www.google.co.uk/this_should_404";

        $retriever->retrieveHtml($url);
    }

    /**
     * @expectedException   Exception
     */
    public function testABadDomainThrowsAnException()
    {
        $retriever = new HtmlRetriever();

        $url = "http://www.thisisnotavaliddomainidontthink.co.uk/this_should_throw_an_exception";

        $retriever->retrieveHtml($url);
    }
}