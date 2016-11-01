<?php

/*
    texto de entrada
        abcabdcbadbc

    divide o texto por linhas
        função separa_linhas ( texto_entrada )
            linha 1 - abcabdcbadbc
            linha 2 - abcabdcbadbc

    para cada linha
        analisa a linha identificando as palavras chaves
            função A ( linhaX )
            função B ( linhaX )
            função C ( linhaX )

*/

$codigo_fonte = $_POST["codigo_fonte"];

class Tradutor
{

    public function divide_texto( $texto )
    /* Função que divide o texto em linhas, colocando cada linha em um índice do array. */
    {

        try {

            $arquivo = "codigo_fonte.txt";

            if ( file_exists($arquivo) )
            /* Prepara o arquivo para a leitura. */
            {
                $abrir = fopen( $arquivo, "w" );
                fwrite( $abrir, $texto );
                fclose( $abrir );
            }

            $linhas = file ( $arquivo ); // Separa as linhas do arquivo em um array.

            $linhas_informacao = array(
                "linha"             => $linhas
            );

            return $linhas_informacao;

        } catch (Exception $e) {
            echo 'Ocorreu um erro ao tentar dividir o código analisado em linhas: ',  $e->getMessage(), "\n";
        }
    }

}

$tradutor = new Tradutor();
$fonte = $tradutor->divide_texto( $codigo_fonte );



