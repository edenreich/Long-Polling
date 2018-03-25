<!DOCTYPE html>
<html>
<head>
	<title>Long-Pooling Demo</title>
</head>
<body>

<div id="recipes">
	<!-- Displays the dynamic menu here -->	
</div>

<script src="../src/js/LongPolling.js"></script>
<script type="text/javascript">
	(function(LongPolling) {

		LongPolling.get('server.php').subscribe(function(recipes) {
			try {
				var recipes = JSON.parse(recipes);
			} catch(e) {
				console.log('Invalid JSON..');
				var recipes = {};
			}

			var div = document.getElementById('recipes');
			var output = '';

			for (var recipe in recipes) {
				output += '<li>Recipe: '+recipe+', Price: '+recipes[recipe]+'</li>'; 
			}
			
			div.innerHTML = output;
		});

	})(LongPolling);
</script>
</body>
</html>