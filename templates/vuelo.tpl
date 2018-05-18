<!DOCTYPE html>
<html>
<head>
	<title></title>
	<style type="text/css">
	.icono-mediano{
		font-size: 40px;
		color: white;
	}
		#detalle{
			width: 100%;
		}
		#error{
			text-align: center;
			background: #4bae49;
			color: white;
			padding: 50px;
			font-size: 2em;
		}
		#encabezados{
			background: #4bae49;
			width: 100%;
			/*height: 100px;*/
			padding: 0;
		}
		#encabezados .principal{
			display: inline-block;
			width: 22%;
			/*outline: 1px solid red;*/
			color: white;
			font-size: 1.5em;
			margin: 1%;
			vertical-align: top;
		}
		#encabezados .principal:last-child{
			background: #57db15;
			height: 100px;
			margin-top: 0;
		}
		.negrita{
			font-weight: bold;
		}
		#datos{
			background: #2f772e;
			padding: 1%;
			/*height: 400px;*/
			width: 98%;
		}
		.det{
			background: #1c621b;
			display: inline-block;
			width: 46%;
			min-height: 320px;
			/*height:320px;*/
			margin: .5%;
			border-radius: 20px;
			vertical-align: top;
			color: #fff;
			padding: 1%;
			text-align: center;
		}
		#tiempos{
			background:#d6d8d5;
			text-align: center;
		}
		#tiempos div{
			display: inline-block;
			width: 32%;
			color: #dd6c14;
			font-size: 1.1em;
			vertical-align: top;
			text-align: center;
		}
		#tiempos div div{
			display: block;
			width: 100%;
			
		}
		
		#tiempos div div:first-child{
			font-weight: bold;
		}
		#tiempos div div:nth-child(2),#tiempos div div:nth-child(3){
				display: inline;
		}
		#detalle a{
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
			#horas{
				margin-top: 40px;
			}
			#horas div div{
				display: inline-block;
				width: 45%;
				text-align: center;		
			}
			#horas  div:nth-child(2) div{
				font-size: 1.5em;
				font-weight: bold;
			}
			.#encabezados h2{
				color: #fff;
			}
			#status div:last-child{
				font-size: 16px;
			}
			.det h3{
				margin-top: 0;
				text-decoration: underline;
			}
			#origen{
				display: inline-block;
				transform: rotate(320deg);
			}
			#destino{
				display: inline-block;
				transform: rotate(20deg);
			}
			.retrazado{
				background:#8B8B29;
			}

		@media(max-width: 768px){
			#encabezados .principal:nth-child(2),#encabezados .principal:nth-child(3){
				display: none;

			}
			#encabezados .principal{
				width: 46%;
			}
		}
		@media(max-width: 480px){
			.det,#encabezados .principal{
				display: block;
				width: 100%;
				margin: 0;
				text-align: center;
			} 
		}

	</style>
</head>
<body>
	
	<div id="detalle">
		{link href="VUELOS" caption="Todos los vuelos"}
		{if count($datos["destinos"])} 

		<div id="encabezados" {if $datos["retrazo"]==true} style="background:#8B8B29;"{/if}>
			<div class="negrita principal">
				<h2>Vuelo <span class="icono-mediano">&#9992<span></h2>
				{$datos["vuelo"]}
			</div>
			{foreach $datos["destinos"] as $valor}
				<div class="principal">
					{foreach $valor as $i}	
						<div>{$i}</div>
					{/foreach}
				</div>
			{/foreach}
		<div class=" negrita principal" id="status" {if $datos["retrazo"]===true} style="background:#E3D822;"{/if}>
			{foreach $datos["status"] as $status}
				<div {if $datos["retrazo"]==true} style="color:#000;"{/if}>{$status}</div>
			{/foreach}
		</div>
		</div>
		<div id="datos" {if $datos["retrazo"]===true} style="background:#4E5323;"{/if}>
			
			<div class="det" {if $datos["retrazo"]===true} style="background:#464B1F;"{/if}>
				<h3>Origen<span id="origen">&#9992</span></h3>
				{foreach $datos["departure_1"] as $info}
					<div>{$info}</div>

				{/foreach}
				<div id="horas">
					<div>
						<div>
							Programado
						</div>
						<div>
							Salida/Estimado
						</div>
					</div>
					<div>
						{foreach $datos["departure_2"] as $hora}
							<div>{$hora}</div>
						{/foreach}
					</div>
				</div>
				
			</div>
			<div class="det" {if $datos["retrazo"]===true} style="background:#464B1F;"{/if}>
				<h3>Destino<span id="destino">&#9992</span></h3>
				{foreach $datos["arrival_1"] as $info}
					<div>{$info}</div>

				{/foreach}
				<div id="horas">
					<div>
						<div>
							Programado
						</div>
						<div>
							llegada/Estimado
						</div>
					</div>
					<div>
						{foreach $datos["arrival_2"] as $hora}
							<div>{$hora}</div>
						{/foreach}
					</div>
				</div>
				
			</div>
			</div>
			<div id="otros">
			<div id="tiempos">
				<h2>Tiempos</h2>
				{if count($datos["tiempos"])}
				{foreach $datos["tiempos"] as $tiempos}
					{foreach $tiempos as $bloque}
						<div>
							{foreach $bloque as $valor}
								<div>{$valor}</div>
							{/foreach}
						</div>
					{/foreach}
				{/foreach}
				{else}
					<p>No hay informacion del tiempo de vuelo.</p>

				{/if}
			</div>

		</div>
		{else}
			<div id="error" class="negrita">
				<p>No hay información sobre el vuelo especificado</p>
			</div>
		{/if}
		</div>
		
	</div>
</body>
</html>