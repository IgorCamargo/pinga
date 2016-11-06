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
            $numero_de_linha = 0;

            foreach ( $linhas as $linhas_num => $linha )
            {
                $frase[$numero_de_linha] = htmlspecialchars( $linha ); // *** NÃO IDENTIFICA O SINAL DE +
                $numero_de_linha++;
            }

            $linhas_informacao = array(
                "total_de_linha"   => $numero_de_linha,
                "frase"             => $frase
            );

            return $linhas_informacao;

        } catch (Exception $e) {
            echo 'Ocorreu um erro ao tentar dividir o código analisado em linhas: ',  $e->getMessage(), "\n";
        }
    }

    public function analise_lexica( $texto )
    /* Identifica TOKENS e LEXEMAS. Só retorna erro se o determinado símbolo não é reconhecido.
    No tradutor você utilizará a análise léxica para identificar os símbolos existentes no seu programa.
    Exemplo, o que é variável, o que é chamada de função, e o que é palavra reservada.
    Após identificar esses símbolos, ficará mais fácil para você realizar a tradução.
    */
    {

        echo $texto["total_de_linha"]."<br>";

        foreach ($texto['frase'] as $t) {
            // foreach ($t as $linhas_num => $k) {
                print_r($t);
                echo "<br>";
            // }
        }
    }

    public function analise_sintatica( $texto )
    /* Identifica erros digitados no código, erros de escritas. */
    {

    }

    public function traducao( $texto )
    {
        // exemplo:     str_replace('vamos beber', '<?php');
    }

}

$tradutor = new Tradutor();
$fonte_fragmentado = $tradutor->divide_texto( $codigo_fonte );
$fonte_lexico = $tradutor->analise_lexica( $fonte_fragmentado );


/*
OBS DO TRABALHO
léxico
sintático
traduzir o código pinga para php

semântica (não precisa)

AO IDENTIFICAR A CRIAÇÃO DE UM IF, POR EXEMPLO, CRIA UM BLOCO DE CÓDIGO, E SE DENTRO DESSE IF HAVER OUTRO IF, CRIADO OUTRO BLOCO DE CÓDIGO

*/