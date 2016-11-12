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

            print_r( $palavras_linha );

            echo "<br>";

            for ($i=0; $i < $cont_palavras_linha; $i++) { 
echo $i."<br>";

                if ( $this->p_reservada($palavras_linha[$i], $linha, $cont_linha, $i) ) {
                    echo "reservada<br>";
                }
                elseif ( $this->p_func_var($palavras_linha[$i], $linha, $cont_linha, $i)  ) {
                    echo "func ou var<br>";
                }
                elseif ( $this->p_simb_oper($palavras_linha[$i], $linha, $cont_linha, $i)  ) {
                    echo "simb ou oper<br>";
                }
                

                // PALAVRA RESERVADA
                // foreach ( $palavra_reservada as $pr )
                // {
                //     if ( ($i < $cont_palavras_linha) && (preg_match( $pr , $palavras_linha[$i] )) )
                //     {
                //         echo "<br>Palavra: <b>".$palavras_linha[$i]."</b><br>",
                //         "Linha: <b>".$linha."</b><br>",
                //         "N° linha: <b>".$cont_linha."</b><br>",
                //         "N° palavra: <b>".($i+1)."</b><br>",
                //         "Tipo: Palavra Reservada: <b>".$palavras_linha[$i]."</b><br>",
                //         "Expressão Regular: <b>".$pr."</b><br></br>";
                //         $i++;
                //     }
                // }

                // FUNÇÃO ou VARIÁVEL
                // foreach ( $funcao_variavel as $fv )
                // {
                //     if ( ($i < $cont_palavras_linha) && (preg_match( $fv , $palavras_linha[$i] )) )
                //     {
                //         echo "<br>Palavra: <b>".$palavras_linha[$i]."</b><br>",
                //         "Linha: <b>".$linha."</b><br>",
                //         "N° linha: <b>".$cont_linha."</b><br>",
                //         "N° palavra: <b>".($i+1)."</b><br>",
                //         "Tipo: Função ou Variável: <b>".$palavras_linha[$i]."</b><br>",
                //         "Expressão Regular: <b>".$fv."</b><br></br>";
                //         $i++;
                //     }
                // }

                // SIMBOLOS OU OPERADORES
                // foreach ( $opererador as $op )
                // {
                //     if ( ($i < $cont_palavras_linha) && (strstr( $palavras_linha[$i], $op )) )
                //     {
                //         echo "<br>Palavra: <b>".$palavras_linha[$i]."</b><br>",
                //         "Linha: <b>".$linha."</b><br>",
                //         "N° linha: <b>".$cont_linha."</b><br>",
                //         "N° palavra: <b>".($i+1)."</b><br>",
                //         "Tipo: <b>Simbolo ou Operador ( ".$palavras_linha[$i]." )</b><br>",
                //         "Expressão Regular: <b>".$op."</b><br></br>";
                //         $i++;
                //     }
                // }

            }
        }

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
                echo "<br>Palavra: <b>".$palavra."</b><br>",
                "Linha: <b>".$linha."</b><br>",
                "N° linha: <b>".$cont_linha."</b><br>",
                "N° palavra: <b>".($i+1)."</b><br>",
                "Tipo: Palavra Reservada: <b>".$palavra."</b><br>",
                "Expressão Regular: <b>".$pr."</b><br></br>";
                return true;
            }
        }
        return false;
    }

    private function p_func_var( $palavra, $linha, $cont_linha, $i )
    /* Identifica se a palavra analisada é função ou variável. */
    {
        // ER
        $funcao_variavel = array(
            "/[0-9a-zA-Z]$/"
        );

        foreach ( $funcao_variavel as $fv )
        {
            if ( preg_match($fv , $palavra) )
            {
                echo "<br>Palavra: <b>".$palavra."</b><br>",
                "Linha: <b>".$linha."</b><br>",
                "N° linha: <b>".$cont_linha."</b><br>",
                "N° palavra: <b>".($i+1)."</b><br>",
                "Tipo: Função ou Variável: <b>".$palavra."</b><br>",
                "Expressão Regular: <b>".$fv."</b><br></br>";
                return true;
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
            ";"
        );
   
        foreach ( $opererador as $op )
        {
            if ( strstr($palavra, $op) )
            {
                echo "<br>Palavra: <b>".$palavra."</b><br>",
                "Linha: <b>".$linha."</b><br>",
                "N° linha: <b>".$cont_linha."</b><br>",
                "N° palavra: <b>".($i+1)."</b><br>",
                "Tipo: <b>Simbolo ou Operador ( ".$palavra." )</b><br>",
                "Expressão Regular: <b>".$op."</b><br></br>";
                return true;
            }
        }
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