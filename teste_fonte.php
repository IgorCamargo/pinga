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

vamosBeber

prepara calcularSoma ( pinga valor1 , pinga valor2 ) {

	pinga valor3 = pinga valor1 + pinga valor2 ;

	deuPorHoje pinga valor3 ;
}
 
pinga resultado = calcularSoma( 1 , 2 ) ;

bebedeira pinga x = 0 , pinga x <= pinga resultado , pinga x++ {
	51 $x > 1 boaIdeia? {
		soltaCana pinga resultado . "<br>" ;
	}
}