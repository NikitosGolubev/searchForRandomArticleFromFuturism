<?php
	// Requiring script that would responsivle for sending requests and getting results
	require_once "search.php";
?>
<!DOCTYPE html>
<html>
<head>
	<title>Find article from futurism</title>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<link href="https://fonts.googleapis.com/css?family=Acme|Inconsolata|Knewave" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="template/css/main.css" />
</head>
<body>
	<div id='mainWrapper'>
		<header>
			<div class='logo'>
				NikitosGolubev - Guzzle Application
			</div>
		</header>
		<main>
			<div class='searchWrap'>
				<div class='searchHeader'>
					<h1>
						Search articles and get random one from <a target='_blank' href='https://futurism.com'>futurism.com</a>
					</h1>
				</div>
				<div class='searchForm'>
					<form name='searchForm' action='/' method='GET'>
						<div class='searchTools'>
							<input type="text" class='searchField' id='JSsearch' name="searchValue" placeholder="Your request..." value='<?php if ($article || $notFound) echo $_GET['searchValue']; ?>' />
							<input type="submit" class='searchBtn' id='JSsearchBtn' name="send" value='Search!' />
						</div>
					</form>
				</div>
			</div>
			<div class='article'>
				<?php if ($notFound): ?>
					<div class='nothingFound'>
						<span class='hotHeader'>Unfortunatelly nothing found :(</span>
					</div>
				<?php endif; ?>
				<?php if ($article): ?>
					<div>
						<span class='articleHeader hotHeader'><?php echo $article['header']; ?></span>
					</div>
					<div class='articleContent'>
						<?php echo $article['content']; ?>
					</div>
				<?php endif; ?>
			</div>
		</main>
		<footer>
			
		</footer>
	</div>
	<script type="text/javascript" src="template/js/searchValidation.js"></script>
</body>
</html>