
var LongPolling = (function(window, undefined) {

	var listener;

	var listenForChanges = function(url, timestamp) {
		var xhr = new XMLHttpRequest() || new ActiveXObject("Microsoft.XMLHTTP");

		var request = (typeof timestamp == 'undefined') ? url : url + '?timestamp=' + timestamp;

		xhr.open('GET', request, true);

		xhr.setRequestHeader('Content-Type', 'application/json');
		xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

		xhr.responseType = 'json';
		xhr.timeout = 25000;

		xhr.onreadystatechange = function() {

			if (this.readyState != 4 || this.status != 200) {
				return;
			}
			
			// It was the first request, or the response was modified.
			// So we need to call the subscribe function with the response.
			if (typeof timestamp == 'undefined' || this.response.changed) {
				listener.call(this, this.response.content);
			}

			// Request timedout, or the data was modified
			// So we send another request.
			listenForChanges(url, this.response.timestamp);
		};

		xhr.onerror = function(message) {
			console.error(message);
		};

		xhr.send(null);
		
		return options;
	};

	var registerListener = function(callback) {
		listener = callback;
	};

	var options = {
		get: listenForChanges,
		subscribe: registerListener
	};

	return options;

})(window);