<?php
	require_once "vendor/autoload.php";
	require_once "SearchArticle.php";

	// Main code

	/* Resulting varriables */

	$article = false;
	$notFound = false;

	// Defining search article object
	$search = new SearchArticle();

	// Attempting to send a search request and get a result
	$html = $search->sendSearchRequest();

	// If request succeeded(all GET parameters are passed right)
	if ($html) {
		// SO I can get html of search results
		$randomArticleURL = $search->getURLOfRandomArticleFromHTMLSearchResult($html);

		// If there no found posts, so nothing found
		if (!$randomArticleURL) $notFound = true;
		else {
			// Getting html content of post
			$articleHTML = $search->getHTMLbyURL($randomArticleURL);
			// If html fetched, so get article data
			if ($articleHTML) $article = $search->handleArticleHTMLAndReturnContent($articleHTML);
		}
	}
?>