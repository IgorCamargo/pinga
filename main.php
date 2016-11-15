<!DOCTYPE html>
<html>
<head>
    <title>Rotulador PHP</title>
    <meta charset="utf-8">
</head>
<body>
    <a href="index.html">
        <button id="novo_rot">Nova verificação</button>
    </a>

    <br></br>

<?php

/*
http://www.regexr.com/
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
                $frase[$numero_de_linha] = $linha;
                $numero_de_linha++;
            }

            $linhas_informacao = array(
                "total_de_linha"   => $numero_de_linha,
                "linha"            => $frase
            );

            return $linhas_informacao;

        } catch (Exception $e) {
            echo 'Ocorreu um erro ao tentar dividir o código analisado em linhas: ',  $e->getMessage(), "\n";
        }
    }

    public function analise_lexica( $texto )
    /* Identifica TOKENS e LEXEMAS. Só retorna erro se o determinado símbolo não é reconhecido. */
    {

        $cont_linha = 0;

        // para cada linha do código fonte realiza as identificações
        foreach ( $texto["linha"] as $linha )
        {
            $cont_linha++;
            $palavras_linha = explode( ' ', $linha );
            $cont_palavras_linha = count( $palavras_linha );

            echo "<br>Linha<br>";
            print_r( $palavras_linha );
            echo "<br>";

            echo "<br>";

            for ($i=0; $i < $cont_palavras_linha; $i++)
            { 
                if ( $inf_token[$i] = $this->p_reservada($palavras_linha[$i], $linha, $cont_linha, $i) ) {
                    print_r($inf_token[$i]);
                    $inf_linha[$i] = $inf_token[$i];
                    // echo "reservada<br>";
                    echo "<br>";
                }
                elseif ( $inf_token[$i] = $this->p_func_var($palavras_linha[$i], $linha, $cont_linha, $i)  ) {
                    print_r($inf_token[$i]);
                    $inf_linha[$i] = $inf_token[$i];
                    // echo "func ou var<br>";
                    echo "<br>";
                }
                elseif ( $inf_token[$i] = $this->p_simb_oper($palavras_linha[$i], $linha, $cont_linha, $i)  ) {
                    print_r($inf_token[$i]);
                    $inf_linha[$i] = $inf_token[$i];
                    // echo "simb ou oper<br>";
                    echo "<br>";
                }
                else {
                    $inf_token[$i] = $this->p_invalida($palavras_linha[$i], $linha, $cont_linha, $i);
                    print_r($inf_token[$i]);
                    $inf_linha[$i] = $inf_token[$i];
                    echo "<br>";
                }
            }
            // echo "<br> ------linha------ <br>";
            // print_r($inf_linha);
            // $inf_codigo[($cont_linha-1)] = $inf_linha;
            // echo "<br> ----------------- <br>";
        }
        echo "<br> ------codigo----- <br>";
        print_r($inf_codigo);
        echo "<br> ----------------- <br>";

        echo "<br>Número total de linhas do código fonte = ".$texto["total_de_linha"];
    }

    private function p_reservada( $palavra, $linha, $cont_linha, $i )
    /* Identifica se a palavra analisada é reservada. */
    {
        // array com as palavras reservadas da linguagem
        $palavra_reservada = array(
            "/\bvamosBeber\b/i",
            "/\bacabouBebida\b/i",
            "/\bsoltaCana\b/i",
            "/\b51\b/i",
            "/\bboaIdeia?\b/i",
            "/\bbavaria!\b/i",
            "/\bsoMaisUmaSe\b/i",
            "/\bpinga\b/i",
            "/\btransformandoDragaoEmPrincesaSe\b/i",
            "/\btropica\b/i",
            "/\bbebedeira\b/i",
            "/\bbira\b/i",
            "/\bcomo\b/i",
            "/\bprepara\b/i",
            "/\bopenbar\b/i",
            "/\bvomito\b/i",
            "/\bbirita\b/i",
            "/\bdeuPorHoje\b/i",
            "/\bdesceUma\b/i"
        );

        foreach ( $palavra_reservada as $pr )
        {
            if ( preg_match($pr , $palavra) )
            {
                $inf_token = array(
                    "token"     => $palavra,
                    "n_linha"   => $cont_linha,
                    "n_palavra" => ($i+1),
                    "tipo"      => "palavra reservada"
                );
                // echo "<br>Palavra: <b>".$palavra."</b><br>",
                // "Linha: <b>".$linha."</b><br>",
                // "N° linha: <b>".$cont_linha."</b><br>",
                // "N° palavra: <b>".($i+1)."</b><br>",
                // "Tipo: Palavra Reservada: <b>".$palavra."</b><br>",
                // "Expressão Regular: <b>".$pr."</b><br></br>";
                return $inf_token;
            }
        }
        return false;
    }

    private function p_func_var( $palavra, $linha, $cont_linha, $i )
    /* Identifica se a palavra analisada é função ou variável. */
    {
        // ER
        $funcao_variavel = array(
            "/[0-9a-zA-Z_]$/"
        );

        foreach ( $funcao_variavel as $fv )
        {
            if ( preg_match($fv , $palavra) )
            {
                $inf_token = array(
                    "token"     => $palavra,
                    "n_linha"   => $cont_linha,
                    "n_palavra" => ($i+1),
                    "tipo"      => "função ou variável"
                );
                // echo "<br>Palavra: <b>".$palavra."</b><br>",
                // "Linha: <b>".$linha."</b><br>",
                // "N° linha: <b>".$cont_linha."</b><br>",
                // "N° palavra: <b>".($i+1)."</b><br>",
                // "Tipo: Função ou Variável: <b>".$palavra."</b><br>",
                // "Expressão Regular: <b>".$fv."</b><br></br>";
                return $inf_token;
            }
        }
        return false;
    }

    private function p_simb_oper( $palavra, $linha, $cont_linha, $i )
    /* Identifica se a palavra analisada é simbolo ou operador. */
    {
        // array com símbolos e operadores
        $opererador = array(
            "+",
            "-",
            "*",
            "/",
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
            "||",
            "{",
            "}",
            "(",
            ")",
            ",",
            ";",
            "."
        );
   
        foreach ( $opererador as $op )
        {
            if ( strstr($palavra, $op) )
            {
                $inf_token = array(
                    "token"     => $palavra,
                    "n_linha"   => $cont_linha,
                    "n_palavra" => ($i+1),
                    "tipo"      => "símbolo ou operador"
                );
                // echo "<br>Palavra: <b>".$palavra."</b><br>",
                // "Linha: <b>".$linha."</b><br>",
                // "N° linha: <b>".$cont_linha."</b><br>",
                // "N° palavra: <b>".($i+1)."</b><br>",
                // "Tipo: <b>Simbolo ou Operador ( ".$palavra." )</b><br>",
                // "Expressão Regular: <b>".$op."</b><br></br>";
                return $inf_token;
            }
        }
        return false;
    }

    // *** AJUSTAR PARA IDENTIFICAR STRING ***
    // private function p_string( $palavra, $linha, $cont_linha, $i )
    // /* Identifica se a palavra analisada é uma string válida. */
    // {
    //     if ( is_string($palavra) ) {
    //         echo "STRING<br>";
    //     }
    //     var_dump($palavra);
    //     echo "<br>";
    //     $inf_token = array(
    //         "token"     => $palavra,
    //         "n_linha"   => $cont_linha,
    //         "n_palavra" => ($i+1),
    //         "tipo"      => "PALAVRA NÃO IDENTIFICADA"
    //     );
    //     return $inf_token;
    // }

    private function p_invalida( $palavra, $linha, $cont_linha, $i )
    /* Identifica se a palavra analisada é nada ou não identificada. */
    {
        if ( preg_match('/[\x21-\x7E]/' , $palavra) ) {
            $inf_token = array(
                "token"     => $palavra,
                "n_linha"   => $cont_linha,
                "n_palavra" => ($i+1),
                "tipo"      => "PALAVRA NÃO IDENTIFICADA"
            );
            return $inf_token;
        }
        // elseif ( preg_match('/[\x22[\x20-\x7E]\x22|\x27[\x20-\x7E]\x27]/' , $palavra) ) {
        //     echo "STRING<br>";
        // }
        // else{
        //     echo "espaço vazio<br>";
        // }
        return false;
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