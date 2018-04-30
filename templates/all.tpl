<!DOCTYPE html>
<html>
<head>
	<title></title>

	<style type="text/css">
			#arrivals,#departures{
				border: 1px solid black;
			}
			th{
				background: silver;
			}
			#vuelos table tr:nth-child(odd){
				background: rgba(78, 226, 109,.2);
				min-height: 40px;
			}
			td{
				text-align: center;
			}
			#vuelos a{
				background-color: #5EBB47;
				border: 1px solid #5dbd00;
				border-radius: 3px;
				color: #FFFFFF;
				/*display: inline-block;*/
				font-family: sans-serif;
				font-size: 16px;
				line-height: 44px;
				text-align: center;
				width: 150px;
				padding: 1%;
				text-decoration: none;

			}


	</style>
</head>
<body>

	<div id="vuelos">
		<h1>Vuelos para hoy en aeropuertos de Cuba({$datos["selected"]})</h1>
<small>Seleccione un aeropuerto</small>{select options="{$aeropuertos}" selected=""}

{space15}

		<h2>LLegadas</h2>
	<table id="arrivals">
		
		{if count($datos["arrivals"])>1}
		<tr>
			<th>Vuelo</th>
			<th>Aerolinea</th>
			<th>Origen</th>
			<th>Llegada</th>
			<th>status</th>
		</tr>
		{foreach $datos["arrivals"] as $filas}
		<tr>
			{foreach $filas as $info}
				<td>{$info}</td>
			{/foreach}
		</tr>
		{/foreach}
		{else}
		<p>No hay proximas llegadas para hoy en este aeropuerto</p>
		{/if}
		
	</table>
	{space10}
	<h2>Salidas</h2>
	<table id="departures">
		
		{if count($datos["departures"])>1}
		<tr>
			<th>Vuelo</th>
			<th>Aerolinea</th>
			<th>Destino</th>
			<th>Salida</th>
			<th>status</th>
		</tr>
		{foreach $datos["departures"] as $filas}
		<tr>
			{foreach $filas as $info}
				<td>{$info}</td>
			{/foreach}
		</tr>
		{/foreach}
		{else}
			<p>No hay proximas salidas para hoy en este aeropuerto</p>
		{/if}
	</table>
	</div>

</body>
</html>