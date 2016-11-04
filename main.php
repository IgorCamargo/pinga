<?php

/*
    texto de entrada
        abcabdcbadbc

    divide o texto por linhas
        função separa_linhas ( texto_entrada )
            linha 1 - abcabdcbadbc
            linha 2 - abcabdcbadbc

    para cada linha
        função realiza a análise léxica
        função realiza a análise sintática
        função realiza a tradução
*/

$codigo_fonte = $_POST["codigo_fonte"];

class Tradutor
{

    public function divide_texto( $texto )
    /* Função que divide o texto em linhas, atribuindo cada linha em um array. */
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

    public function analise_lexica( $texto )
    {
        // ao identificar um erro, parar tradução?
    }

    public function analise_sintatica( $texto )
    {

    }

    public function traducao( $texto )
    {
        // exemplo:     str_replace('vamos beber', '<?php');
    }

}

$tradutor = new Tradutor();
$fonte_fragmentado = $tradutor->divide_texto( $codigo_fonte );


/*
OBS DO TRABALHO
léxico
sintático
traduzir o código pinga para php

semântica (não precisa)

AO IDENTIFICAR A CRIAÇÃO DE UM IF, POR EXEMPLO, CRIA UM BLOCO DE CÓDIGO, E SE DENTRO DESSE IF HAVER OUTRO IF, CRIADO OUTRO BLOCO DE CÓDIGO

*/