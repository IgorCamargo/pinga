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

        $cont_linha = 0;

        foreach ( $texto["frase"] as $linha )
        {
            $cont_linha++;

            $palavras_linha = explode( ' ', $linha );


            // print_r($palavras_linha);
            // echo " ||| teste1<br>";
            // print_r($linha);
            // echo " ||| teste2<br><br>";

            // aqui analiso cada palavra de cada frase
            for ( $i=0; $i < count( $palavras_linha ); $i++ )
            {
                
                // PALAVRAS Reservada** AJUSTAR MELHOR A LOGICA
                // VAMOS BEBER
                if ( preg_match( "/\bvamos\b/i" , $palavras_linha[$i] ) )
                {
                    if ( preg_match( "/\bbeber\b/i" , $palavras_linha[$i+1] ) )
                    {
                        echo $linha."<br>",
                        "<b>".$palavras_linha[$i]." ".$palavras_linha[$i+1]." | Palavra Reservada</b><br>",
                        "Expressão Regular: /\bvamos\b/i e /\bbeber\b/i<br></br>";
                    }
                }
                else
                {
                    echo "ERRO! Comando esperado 'vamos beber' não foi reconhecido na linha ".$cont_linha;
                }

                // ACABOU A CANA
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
                }
                else
                {
                    echo "ERRO! Comando esperado 'acabou a bebida' não foi reconhecido na linha ".$cont_linha;
                }

                // SOLTA CANA
                if ( preg_match( "/\bsolta\b/i" , $palavras_linha[$i] ) )
                {
                    if ( preg_match( "/\bcana\b/i" , $palavras_linha[$i+1] ) )
                    {
                        echo $linha."<br>",
                        "<b>".$palavras_linha[$i]." ".$palavras_linha[$i+1]." | Palavra Reservada</b><br>",
                        "Expressão Regular: /\bsolta\b/i e /\bcana\b/i<br></br>";
                    }
                }
                else
                {
                    echo "ERRO! Comando esperado 'solta cana' não foi reconhecido na linha ".$cont_linha;
                }

                // 51 condição BOA IDEIA?
                if ( preg_match( "/\b51\b/i" , $palavras_linha[$i] ) )
                {
                    if ( preg_match( "/\bboa\b/i" , $palavras_linha[$i+2] ) )
                    {
                        if ( preg_match( "/\bideia?\b/i" , $palavras_linha[$i+3] ) )
                        {
                            echo $linha."<br>",
                            "<b>".$palavras_linha[$i]." CONDIÇÃO ".$palavras_linha[$i+2]." ".$palavras_linha[$i+3]." | Palavra Reservada</b><br>",
                            "Expressão Regular: /\b51\b/i e /\bboa\b/i e /\bideia?\b/i<br></br>";
                        }
                    }
                }
                else
                {
                    echo "ERRO! Comando esperado '51 CONDIÇÃO boa ideia?' não foi reconhecido na linha ".$cont_linha;
                }

                // ELSE
                if ( preg_match( "/\bbavaria!\b/i" , $palavras_linha[$i] ) )
                {
                    echo $linha."<br>",
                    "<b>".$palavras_linha[$i]." | Palavra Reservada</b><br>",
                    "Expressão Regular: /\bbavaria!\b/i<br></br>";
                }
                else
                {
                    echo "ERRO! Comando esperado 'else' não foi reconhecido na linha ".$cont_linha;
                }

                // SO MAIS UMA SE condiçâo
                if ( preg_match( "/\bso\b/i" , $palavras_linha[$i] ) )
                {
                    if ( preg_match( "/\bmais\b/i" , $palavras_linha[$i+1] ) )
                    {
                        if ( preg_match( "/\buma\b/i" , $palavras_linha[$i+2] ) )
                        {
                            if ( preg_match( "/\bse\b/i" , $palavras_linha[$i+3] ) )
                            {
                                echo $linha."<br>",
                                "<b>".$palavras_linha[$i]." ".$palavras_linha[$i+1]." ".$palavras_linha[$i+2]." ".$palavras_linha[$i+3]." CONDIÇÃO | Palavra Reservada</b><br>",
                                "Expressão Regular: /\bso\b/i e /\bmais\b/i e /\buma\b/i e /\bse\b/i<br></br>";
                            }
                        }
                    }
                }
                else
                {
                    echo "ERRO! Comando esperado 'so mais uma se CONDIÇÂO' não foi reconhecido na linha ".$cont_linha;
                }

                // PINGA variavel
                if ( preg_match( "/\bpinga\b/i" , $palavras_linha[$i] ) )
                {
                    if ( preg_match( "/\b[^0-9A-Za-z]\b/" , $palavras_linha[$i+1] ) )
                    {
                        echo $linha."<br>",
                        "<b>".$palavras_linha[$i]." | Palavra Reservada</b><br>",
                        "Expressão Regular: /\bpinga\b/i<br></br>";
                    }
                }
                else
                {
                    echo "ERRO! Comando esperado 'solta cana' não foi reconhecido na linha ".$cont_linha;
                }

                // TRANSFORMANDO DRAGAO EM PRINCESA SE condiçâo
                if ( preg_match( "/\btransformando\b/i" , $palavras_linha[$i] ) )
                {
                    if ( preg_match( "/\bdragao\b/i" , $palavras_linha[$i+1] ) )
                    {
                        if ( preg_match( "/\bem\b/i" , $palavras_linha[$i+2] ) )
                        {
                            if ( preg_match( "/\bprincesa\b/i" , $palavras_linha[$i+3] ) )
                            {
                                if ( preg_match( "/\bse\b/i" , $palavras_linha[$i+4] ) )
                                {
                                    echo $linha."<br>",
                                    "<b>".$palavras_linha[$i]." ".$palavras_linha[$i+1]." ".$palavras_linha[$i+2]." ".$palavras_linha[$i+3]." ".$palavras_linha[$i+4]." CONDIÇÃO | Palavra Reservada</b><br>",
                                    "Expressão Regular: /\btransformando\b/i e /\bdragao\b/i e /\bem\b/i e /\bprincesa\b/i e /\bse\b/i<br></br>";
                                }
                            }
                        }
                    }
                }
                else
                {
                    echo "ERRO! Comando esperado 'transformando dragao em princesa se CONDIÇÂO' não foi reconhecido na linha ".$cont_linha;
                }

                // DO
                if ( preg_match( "/\btropica\b/i" , $palavras_linha[$i] ) )
                {
                    echo $linha."<br>",
                    "<b>".$palavras_linha[$i]." | Palavra Reservada</b><br>",
                    "Expressão Regular: /\btropica\b/i<br></br>";
                }
                else
                {
                    echo "ERRO! Comando esperado 'tropica' não foi reconhecido na linha ".$cont_linha;
                }

                // TOMAR v_inicial ATE condicao_passo ASSIM ESQUECENDO contador **** atribuição de variavel não pode ter espaço
                if ( preg_match( "/\btomar\b/i" , $palavras_linha[$i] ) )
                {
                    if ( preg_match( "/\bate\b/i" , $palavras_linha[$i+2] ) )
                    {
                        if ( preg_match( "/\assim\b/i" , $palavras_linha[$i+4] ) )
                        {
                            if ( preg_match( "/\besquecendo\b/i" , $palavras_linha[$i+5] ) )
                            {
                                echo $linha."<br>",
                                "<b>".$palavras_linha[$i]." V_INICIAL ".$palavras_linha[$i+2]." CONDICAO_PASSO ".$palavras_linha[$i+4]." ".$palavras_linha[$i+5]." CONTADOR | Palavra Reservada</b><br>",
                                "Expressão Regular: /\btomar\b/i e /\bate\b/i e /\bassim\b/i e /\besquecendo\b/i<br></br>";
                            }
                        }
                    }
                }
                else
                {
                    echo "ERRO! Comando esperado 'tomar V_INICIAL até CONDIÇ O PASSO assim esquecendo CONTADOR' não foi reconhecido na linha ".$cont_linha;
                }

                // BIRA array COMO array_apelido
                if ( preg_match( "/\bbira\b/i" , $palavras_linha[$i] ) )
                {
                    if ( preg_match( "/\bcomo\b/i" , $palavras_linha[$i+2] ) )
                    {
                        echo $linha."<br>",
                        "<b>".$palavras_linha[$i]." ".$palavras_linha[$i+2]." | Palavra Reservada</b><br>",
                        "Expressão Regular: /\bbira\b/i e /\bcomo\b/i<br></br>";
                    }
                }
                else
                {
                    echo "ERRO! Comando esperado 'bira ARRAY como ARRAY_APELIDO' não foi reconhecido na linha ".$cont_linha;
                }
                
                // PREPARA UMA
                if ( preg_match( "/\bprepara\b/i" , $palavras_linha[$i] ) )
                {
                    if ( preg_match( "/\buma\b/i" , $palavras_linha[$i+1] ) )
                    {
                        echo $linha."<br>",
                        "<b>".$palavras_linha[$i]." ".$palavras_linha[$i+1]." | Palavra Reservada</b><br>",
                        "Expressão Regular: /\bprepara\b/i e /\buma\b/i<br></br>";
                    }
                }
                else
                {
                    echo "ERRO! Comando esperado 'prepara uma' não foi reconhecido na linha ".$cont_linha;
                }

                // PAGO RODADA DE
                if ( preg_match( "/\bpago\b/i" , $palavras_linha[$i] ) )
                {
                    if ( preg_match( "/\brodada\b/i" , $palavras_linha[$i+1] ) )
                    {
                        if ( preg_match( "/\bde\b/i" , $palavras_linha[$i+2] ) )
                        {
                            echo $linha."<br>",
                            "<b>".$palavras_linha[$i]." ".$palavras_linha[$i+1]." ".$palavras_linha[$i+2]." | Palavra Reservada</b><br>",
                            "Expressão Regular: /\bpago\b/i e /\brodada\b/i e /\bde\b/i<br></br>";
                        }
                    }
                }
                else
                {
                    echo "ERRO! Comando esperado 'pago rodada de' não foi reconhecido na linha ".$cont_linha;
                }

                // VOMITO
                if ( preg_match( "/\bvomito\b/i" , $palavras_linha[$i] ) )
                {
                    echo $linha."<br>",
                    "<b>".$palavras_linha[$i]." | Palavra Reservada</b><br>",
                    "Expressão Regular: /\bvomito\b/i<br></br>";
                }
                else
                {
                    echo "ERRO! Comando esperado 'vomito' não foi reconhecido na linha ".$cont_linha;
                }

                // BIRITA
                if ( preg_match( "/\bbirita\b/i" , $palavras_linha[$i] ) )
                {
                    echo $linha."<br>",
                    "<b>".$palavras_linha[$i]." | Palavra Reservada</b><br>",
                    "Expressão Regular: /\bbirita\b/i<br></br>";
                }
                else
                {
                    echo "ERRO! Comando esperado 'birita' não foi reconhecido na linha ".$cont_linha;
                }

                // DEU POR HOJE
                if ( preg_match( "/\bdeu\b/i" , $palavras_linha[$i] ) )
                {
                    if ( preg_match( "/\bpor\b/i" , $palavras_linha[$i+1] ) )
                    {
                        if ( preg_match( "/\bhoje\b/i" , $palavras_linha[$i+2] ) )
                        {
                            echo $linha."<br>",
                            "<b>".$palavras_linha[$i]." ".$palavras_linha[$i+1]." ".$palavras_linha[$i+2]." | Palavra Reservada</b><br>",
                            "Expressão Regular: /\bdeu\b/i e /\bpor\b/i e /\bhoje\b/i<br></br>";
                        }
                    }
                }
                else
                {
                    echo "ERRO! Comando esperado 'deu por hoje' não foi reconhecido na linha ".$cont_linha;
                }

                // DESCE UMA
                if ( preg_match( "/\bdesce\b/i" , $palavras_linha[$i] ) )
                {
                    if ( preg_match( "/\buma\b/i" , $palavras_linha[$i+1] ) )
                    {
                        echo $linha."<br>",
                        "<b>".$palavras_linha[$i]." ".$palavras_linha[$i+1]." | Palavra Reservada</b><br>",
                        "Expressão Regular: /\bdesce\b/i e /\buma\b/i<br></br>";
                    }
                }
                else
                {
                    echo "ERRO! Comando esperado 'desce uma' não foi reconhecido na linha ".$cont_linha;
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