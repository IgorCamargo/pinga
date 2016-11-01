<?php

$codigo_fonte = $_POST["codigo_fonte"];

class Tradutor
{

    public function quebra_frase( $texto )    // função que analisa por linha
    {
        $arquivo = "codigo_fonte.txt";

        if ( file_exists($arquivo) )
        {
            $abrir = fopen( $arquivo, "w" );
            fwrite( $abrir, $texto );
            fclose( $abrir );
        }

        $linhas = file ( $arquivo );
        $numero_de_linha = 0;

        foreach ( $linhas as $linhas_num => $linha )
        {
            echo "<br>".$frase[$numero_de_linha] = "Linha{$linhas_num} ".htmlspecialchars( $linha );
            $numero_de_linha++;
        }

        $frases_informacao = array(
            "numero_de_linha"   => $numero_de_linha,
            "frase"             => $frase
        );

        return $frases_informacao;
    }

}

$tradutor = new Tradutor();
$fonte = $tradutor->quebra_frase( $codigo_fonte );




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