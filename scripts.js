function check() {
	// get airport
	var airport = $('#airport').val();
	var arrivals = $('#arrivals').prop('checked');
	var departures = $('#departures').prop('checked');

	// do not allow empty searches
	if(!airport || (!arrivals && !departures)) {
		M.toast({html: 'Debe escoger aeropuerto y salidas o llegadas'});
		return false;
	}

	// get the type 
	var type = 'departures';
	if(arrivals) var type = 'arrivals';

	// send the request
	getBoard(airport, type);
}

function getBoard(airport, type) {
	apretaste.send({
		'command':'VUELOS BOARD',
		'data':{'airport':airport, 'type':type}
	});
}