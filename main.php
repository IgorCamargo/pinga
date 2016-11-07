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

        // echo $texto["total_de_linha"]."<br>";

        // foreach ($texto['frase'] as $t) {
        //     print_r($t);
        //     echo "<br>";
        // }
        $palavra_reservada = array(
            "/\bvamos beber\b/i",
            "/\bsem bebida acabou a festa\b/i",
            "/\bpago a rodada de\b/i",
            "/\bvomito\b/i",
            "/\bbirita\b/i",
            "/\bprepara uma\b/i",
            "/\bdeu por hoje\b/i",
            "/\btomar\b/i",
            "/\btransformando dragao em princesa se\b/i",
            "/\btropica\b/i",
            "/\b51\b/i",
            "/\bbavaria!\b/i",
            "/\bso mais uma se\b/i",
            "/{|}/",
        );

        $operador = array(
            "+",
            "-",
            "*",
            "/",
            // aqui não tenho certeza se são operadores
            "=",
            "++",
            "==",
            "<",
            ">",
            "<=",
            ">=",
            "!",
            "!=",
            "<>",
            "&&",
            "||"
        );

        $funcao_variavel = array(
            "/\bpinga\b/i",
            "/\b[^0-9A-Za-z]\b/"
        );

        foreach ( $texto["frase"] as $linha )
        {
            $palavras_linha = explode( ' ', $linha );


            // print_r($palavras_linha);
            // echo " ||| teste1<br>";
            // print_r($linha);
            // echo " ||| teste2<br><br>";



            // aqui analiso cada palavra de cada frase
            for ( $i=0; $i < count( $palavras_linha ); $i++ )
            {
                
                // PALAVRAS Reservada** AJUSTAR MELHOR A LOGICA
                if ( preg_match( "/\bvamos\b/i" , $palavras_linha[$i] ) )
                {
                    if ( preg_match( "/\bbeber\b/i" , $palavras_linha[$i+1] ) )
                    {
                        echo $linha."<br>",
                        "<b>".$palavras_linha[$i]." ".$palavras_linha[$i+1]." | Palavra Reservada</b><br>",
                        "Expressão Regular: /\bvamos\b/i e /\bbeber\b/i<br></br>";
                    }
                    else
                    {
                        echo "ERRO! Comando esperaro 'vamos beber' não foi reconhecido na linha ".($i+1);
                    }
                }

                if ( preg_match( "/\bacabou\b/i" , $palavras_linha[$i] ) )
                {
                    if ( preg_match( "/\ba\b/i" , $palavras_linha[$i+1] ) )
                    {
                        if ( preg_match( "/\bbebida\b/i" , $palavras_linha[$i+2] ) )
                        {
                            echo $linha."<br>",
                            "<b>".$palavras_linha[$i]." ".$palavras_linha[$i+1]." ".$palavras_linha[$i+2]." | Palavra Reservada</b><br>",
                            "Expressão Regular: /\bacabou\b/i e /\ba\b/i e /\bbebida\b/i<br></br>";
                        }
                    }
                    else
                    {
                        echo "ERRO! Comando esperaro 'vamos beber' não foi reconhecido na linha ".($i+1);
                    }
                }



            }

                            // OPERADOR
                            // foreach ( $operador as $op )
                            // {
                            //     if ( strstr( $palavras_linha[$i], $op ) )
                            //     {
                            //         echo $linha."<br>",
                            //         "<b>".$palavras_linha[$i]." | Operador</b><br>",
                            //         "Expressão Regular: ".$op."<br></br>";
                            //     }
                            // }

                            // FUNÇÃO e VARIÁVEL
                            // foreach ( $funcao_variavel as $fu )
                            // {
                                // identifica função *** AJUSTAR
                                // if ( preg_match( $fu , $palavras_linha[$i] ) )
                                // {
                                //     echo $linha."<br>",
                                //     "<b>".$palavras_linha[$i]." | Função</b><br>",
                                //     "Expressão Regular: ".$fu."(<br></br>";
                                // }
                                // identifica variável
                            //     if ( strstr( $palavras_linha[$i], "$" ) > 0 )
                            //     {
                            //         echo $linha."<br>",
                            //         "<b>".$palavras_linha[$i]." | Variável</b><br>",
                            //         "Expressão Regular: ".$fu."$<br></br>";
                            //     }
                            // }
        }
        echo "<br>Número total de linhas do código fonte = ".$texto["total_de_linha"];
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