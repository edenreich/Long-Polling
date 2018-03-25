# Long-Polling

A intuitive and clean way to write long-polling applications


# Installation

For the javascript module:
Simply import src/js/LongPolling.js into your project and embed in the html

For the PHP file you can use composer:
```sh
composer require reich/longpolling
```


# Usage

On the server side you need to listen for changes with the following code snippet:
```php
\Reich\PHP\LongPolling::check(1000, function() {
	// return your data you wish to the client.
});
```

Here we are checking every second for data changes, if the returned value was not changed, then nothing will happend. If the data infact changed, the client will recieve the new data.

On the client side using javascript we need to set a listener for recieving the changed data:
```html
<div id="recipes">
	<!-- Loading the modified recipes from the server -->
</div>
```

```javascript

	(function(LongPolling) {

		LongPolling.get('server.php').subscribe(function(recipes) {
			var recipes = JSON.parse(recipes);
			
			var div = document.getElementById('recipes');
			var output = '';

			for (var recipe in recipes) {
				output += '<li>Recipe: '+recipe+', Price: '+recipes[recipe]+'</li>'; 
			}
			
			div.innerHTML = output;
		});

	})(LongPolling);

```

# Notes

This technik is used by many big platforms such as facebook, twitter and many other rich internet applications out there.

It is very simple concept, yet very powerful. Now our users do not need to refresh their browser for changes :)