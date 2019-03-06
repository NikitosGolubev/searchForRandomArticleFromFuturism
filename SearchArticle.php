<?php

require_once "vendor/autoload.php"; // requiring packagies

use GuzzleHttp\Client;

/*
	Class that is responsible for everything demanded for organization searching at other site
	Particularly:
	-) Validation params values that user typed
	-) Creating and sending requests
	-) Getting result and handling it
*/
class SearchArticle {
    // Site index page
    private $indexPageUrl = "https://futurism.com";
	// The site where search will be performed
	private $requestURL = "https://futurism.com/search";
	// Min and max length of search string
	private $minSearchRequestLen = 3;
	private $maxSearchRequestLen = 256;

	// Property that shows if user sent any data that may be searched
	private $isDataSent;
	// Property that contains a data that should be searched for 
	private $searchValue;

	public function __construct() {
		// Checking if user has sent needed data
		if (isset($_GET['send']) && isset($_GET['searchValue'])) {
			$this->isDataSent = true;
			$this->searchValue = $this->trim($_GET['searchValue']); // cutting useless spaces
		}
	}

	// Function that sends request and gets result if possible
	public function sendSearchRequest() {
		// If needed data is sent and its verified
		if ($this->isDataSent && $this->validateSearchValue($this->searchValue)) {
			// Getting html of search results
			$html = $this->getHTMLbyURL($this->requestURL.'?q='.$this->searchValue);

			// If html fetched, so return it
			if ($html) return $html;
			else return false;
		}
		else return false;
	}


	/*
		Function that parses HTML of searched result, selects all the links to found posts and chooses
		random one from the whole stack, if nothing found - returns false
	*/
	public function getURLOfRandomArticleFromHTMLSearchResult($htmlSearchResult) {
		// I use phpQuery library in order to parse html conviniently
		phpQuery::newDocument($htmlSearchResult);
			// Getting all links to found posts by selector
			$linksToArticlesCollection = pq(".ResultsRow a");
			// URLs to found articles will contain there
			$searchedArticlesURLs = [];

			// If something found
			if ($linksToArticlesCollection->html()) {
				foreach ($linksToArticlesCollection as $linkTag) {
					// Getting every single link and fetching URL from href attribute
					$articleURL = pq($linkTag)->attr('href');
					// Put URL into URLs storage
					$searchedArticlesURLs[] = $articleURL;
				}
			}
			else return false;

			// Getting random index or array thant contains URLs. Generating int [0, array len - 1]
			$randomArticleURLIndex = random_int(0, (count($searchedArticlesURLs) - 1));
		phpQuery::unloadDocuments(); // Clear memory

        // Getting URI by random index and returning it
        $random_uri = $searchedArticlesURLs[$randomArticleURLIndex];

        // Forming full url
        $url = $this->indexPageUrl.$random_uri;

		return $url;
	}

	// Function that gets html from some web page by its URL
	public function getHTMLbyURL($url) {
		// Guzzle
		$client = new Client();

		$response = $client->get($url);
		$html = $response->getBody();

		if ($html) return $html;
		return false;
	}

	// Function that forms data about article based on HTML of article page
	public function handleArticleHTMLAndReturnContent($html) {
		// phpQuery library
		phpQuery::newDocument($html);
			$articleHeader = pq('.Title h1')->text(); // getting header of article
			$articleContent = pq('.article')->html(); // getting content of article
		phpQuery::unloadDocuments();

		// Put all the data into array
		$article = ['header' => $articleHeader, "content" => $articleContent];
		return $article;
	}

	// Function that validates search string to not to make useless requests
	private function validateSearchValue($searchValueString) {
		// Checking if length of string is satisfying the max and min barriers
		if (mb_strlen($searchValueString) < $this->minSearchRequestLen) return false;
		if (mb_strlen($searchValueString) > $this->maxSearchRequestLen) return false;
		return true;
	}

	// Function that cuts all useles spaces
	private function trim($string) {
		// Change all multiple spaces to solo space "    " -> " "
		$validString = preg_replace("/\s\s+/", " ", $string);
		return $validString;
	}
}