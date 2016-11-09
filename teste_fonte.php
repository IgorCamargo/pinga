<?php

function calcularSoma( $valor1, $valor2 ) {

	$valor3 = $valor1 + $valor2;

	return $valor3;
}
 
$resultado = calcularSoma( 1, 2 );

for ( $x = 0; $x <= $resultado; $x++ ) {
	if ( $x > 1 ) {
		echo $resultado."<br>";
	}
}

/*

vamos beber

prepara uma calcularSoma( pinga valor1, pinga $valor2) {

	pinga valor3 = pinga valor1 + pinga valor2;

	deu por hoje pinga valor3;
}
 
pinga resultado = calcularSoma( 1, 2 );

beber pinga x=0 até pinga x<= pinga resultado assim esqueço pinga x++ {
	51 $x > 1 boa ideia? {
		solta cana pinga resultado."<br>";
	}
}