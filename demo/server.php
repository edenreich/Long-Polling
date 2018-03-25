<?php

require_once __DIR__ . '/../vendor/autoload.php';

// Set the timer for checking in miliseconds

\Reich\PHP\LongPolling::check(300, function() {

	// This could be anything, for the demo I've chosen a file.
	// You could also use a dynamic data from the database. 
	$recipes = file_get_contents('data.json');

	// Make sure you return it, this will return the data to the client 
	// if it has been changed sense the last time it was returned or if 20 seconds timed out.
	return $recipes;
});