<style type="text/css">
	.icono-mediano {
		font-size: 40px;
		color: white;
	}

	#detalle {
		width: 100%;
	}

	#error {
		text-align: center;
		background: #4bae49;
		color: white;
		padding: 50px;
		font-size: 2em;
	}

	#encabezados {
		background: #4bae49;
		width: 100%;
		/*height: 100px;*/
		padding: 0;
	}

	#encabezados .principal {
		display: inline-block;
		width: 22%;
		/*outline: 1px solid red;*/
		color: white;
		font-size: 1.5em;
		margin: 1%;
		vertical-align: top;
	}

	#encabezados .principal:last-child {
		background: #57db15;
		height: 100px;
		margin-top: 0;
	}

	.negrita {
		font-weight: bold;
	}

	#datos {
		background: #2f772e;
		padding: 1%;
		/*height: 400px;*/
		width: 98%;
	}

	.det {
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

	#tiempos {
		background: #d6d8d5;
		text-align: center;
	}

	#tiempos div {
		display: inline-block;
		width: 32%;
		color: #dd6c14;
		font-size: 1.1em;
		vertical-align: top;
		text-align: center;
	}

	#tiempos div div {
		display: block;
		width: 100%;

	}

	#tiempos div div:first-child {
		font-weight: bold;
	}

	#tiempos div div:nth-child(2),
	#tiempos div div:nth-child(3) {
		display: inline;
	}

	#detalle a {
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

	.#encabezados h2 {
		color: #fff;
	}

	#status div:last-child {
		font-size: 16px;
	}

	.det h3 {
		margin-top: 0;
		text-decoration: underline;
	}

	#origen {
		display: inline-block;
		transform: rotate(320deg);
	}

	#destino {
		display: inline-block;
		transform: rotate(20deg);
	}

	.retrazado {
		background: #8B8B29;
	}

	.centrado {
		text-align: center;
	}

	/***********nuevp estilo*******************/

	#head {
		height: 3em;
		background-color: #1C621B;
		color: white;
		font-size: 2.5em;
		font-weight: bold;
		text-align: center;
	}

	#aerolinea {
		font-size: .8em;
	}

	#estado {
		background-color: #FF7811;
		font-size: .5em;
		font-size: 1.5em;
		color: white;

	}

	.horas h2 {
		text-align: center;
	}

	.horas>div {
		display: inline-block;
		width: 48%;
		text-align: center;
	}

	@media(max-width: 768px) {
		#encabezados .principal:nth-child(2),
		#encabezados .principal:nth-child(3) {
			display: none;

		}
		#encabezados .principal {
			width: 46%;
		}
	}

	@media(max-width: 480px) {
		.det,
		#encabezados .principal {
			display: block;
			width: 100%;
			margin: 0;
			text-align: left;
		}
		.det {
			margin-bottom: 1em;
		}
		div {
			text-align: left;
		}

	}
</style>
<center>
	<div id="detalle">
		{link href="VUELOS" caption="Todos los vuelos"}
		<div id="encabezados">
			<div id="head">
				<div id="numero">{$datos["num_vuelo"][0]}</div>
				<div id="aerolinea">{$datos["aerolinea"][0]}</div>
			</div>
		</div>
		<div id="datos">
			{if count($datos['dep'])>1}
			<div class="centrado">{$datos["titulo-secundario"][0]}</div>
			<div id="estado" class="centrado">{$datos["status-vuelo"][0]}</div>

			<div class="det">
				<h3>Origen
					<span id="origen">&#9992;</span>
				</h3>
				{foreach $datos["dep"][0] as $info}
				<div>{$info}</div>
				{/foreach}
			</div>

			<div class="det">
				<h3>Destino
					<span id="destino">&#9992;</span>
				</h3>
				{foreach $datos["arr"][0] as $info}
				<div>{$info}</div>
				{/foreach}
			</div>

			<h2 class="centrado">{$datos["duracion"]}</h2>
			<div class="centrado">{$datos["titulo-secundario"][1]}</div>
			<div id="estado" class="centrado">{$datos["status-vuelo"][1]}</div>
			<div class="det">
				<h3>Origen
					<span id="origen">&#9992;</span>
				</h3>
				{foreach $datos["dep"][1] as $info}
				<div>{$info}</div>
				{/foreach}
			</div>
			<div class="det">
				<h3>Destino
					<span id="destino">&#9992;</span>
				</h3>
				{foreach $datos["arr"][1] as $info}
				<div>{$info}</div>
				{/foreach}
			</div>
			<h2 class="centrado">{$datos["duracion"]}</h2>
			{else}
			<div class="centrado">{$datos["titulo-secundario"][0]}</div>
			<div class="det">
				<h3>Origen
					<span id="origen">&#9992;</span>
				</h3>
				{foreach $datos["dep"][0] as $info}
				<div>{$info}</div>
				{/foreach}
			</div>
			<div class="det">
				<h3>Destino
					<span id="destino">&#9992;</span>
				</h3>
				{foreach $datos["arr"][0] as $info}
				<div>{$info}</div>
				{/foreach}
			</div>
			{/if}
		</div>
		{space10}
		<div class="horas">
			<h2>Hora actual</h2>
			<div id="hora-actuale-1">
				{foreach $datos["hora-actual-1"] as $info}
				<div>{$info}</div>
				{/foreach}
			</div>
			<div class="hora-actual-2">
				{foreach $datos["hora-actual-2"] as $info}
				<div>{$info}</div>
				{/foreach}
			</div>
		</div>
	</div>
</center>