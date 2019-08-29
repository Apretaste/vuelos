//
// initialize the select
//
$(document).ready(function(){
	$('select').formSelect();
});

//
// check if the info is correct and load the airport board
//
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

//
// load the airport board
//
function getBoard(airport, type) {
	apretaste.send({
		'command':'VUELOS BOARD',
		'data':{'airport':airport, 'type':type}
	});
}