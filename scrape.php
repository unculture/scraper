<?php

use Unculture\Scraper\ScrapeCommand;

require_once 'vendor/autoload.php';

$cmd = new Commando\Command();

$cmd->setHelp("
Scrapes the Sainsbury's grocery site - Ripe Fruits page and returns a JSON array
of all the products on the page. The URL for the page is currently hard coded into the application.
");

// The product list page URL
$url = "http://www.sainsburys.co.uk/webapp/wcs/stores/servlet/CategoryDisplay?listView=true&orderBy=FAVOURITES_FIRST&parent_category_rn=12518&top_category=12518&langId=44&beginIndex=0&pageSize=20&catalogId=10137&searchTerm=&categoryId=185749&listId=&storeId=10151&promotionId=#langId=44&storeId=10151&catalogId=10137&categoryId=185749&parent_category_rn=12518&top_category=12518&pageSize=20&orderBy=FAVOURITES_FIRST&searchTerm=&beginIndex=0&hideFilters=true";

$command = new ScrapeCommand();
$command->execute($url);


