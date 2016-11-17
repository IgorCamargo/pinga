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
        $inf_codigo = array();

        // para cada linha do código fonte realiza as identificações
        foreach ( $texto["linha"] as $linha )
        {
            $cont_linha++;
            $palavras_linha = explode( ' ', $linha );
            $cont_palavras_linha = count( $palavras_linha );

            // echo "<br>--------- Linha ---------<br>";
            // print_r( $palavras_linha );
            // echo "<br>-------------------------<br>";

            // para cada palavra realiza a análise
            for ($i=0; $i < $cont_palavras_linha; $i++)
            { 
                if ( $inf_linha[$i] = $this->p_reservada($palavras_linha[$i], $linha, $cont_linha, $i) ) {
                    // print_r($inf_linha[$i]);
                    // echo "reservada<br>";
                    array_push( $inf_codigo, $inf_linha[$i] );
                }
                elseif ( $inf_linha[$i] = $this->p_func_var($palavras_linha[$i], $linha, $cont_linha, $i) ) {
                    // print_r($inf_linha[$i]);
                    // echo "func ou var<br>";
                    array_push( $inf_codigo, $inf_linha[$i] );
                }
                elseif ( $inf_linha[$i] = $this->p_simb_oper($palavras_linha[$i], $linha, $cont_linha, $i) ) {
                    // print_r($inf_linha[$i]);
                    // echo "simb ou oper<br>";
                    array_push( $inf_codigo, $inf_linha[$i] );
                }
                // elseif ( $inf_linha[$i] = $this->p_string($palavras_linha[$i], $linha, $cont_linha, $i) ) {
                //     print_r($inf_linha[$i]);
                //     // echo "simb ou oper<br>";
                //     echo "<br>";
                // }
                elseif ( $inf_linha[$i] = $this->p_invalida($palavras_linha[$i], $linha, $cont_linha, $i) ) {
                    // $inf_linha[$i] = "erro";
                    // print_r($inf_linha[$i]);
                    array_push( $inf_codigo, $inf_linha[$i] );
                // } else {
                    // $inf_linha[$i] = "espaço branco";
                    // print_r($inf_linha[$i]);
                    // array_push( $inf_codigo, $inf_linha[$i] );
                }
            }
            // echo "<br>EEEEIIIIITTTAAA<br>";
            // print_r( $inf_codigo );
            // echo "<br>===============<br>";
        }
        // echo "<br> ------codigo----- <br>";
        // print_r($inf_codigo);
        // echo "<br> ----------------- <br>";

        // echo "<br>Número total de linhas do código fonte = ".$texto["total_de_linha"];

        $lex_codigo = array(
            "inf_codigo"    => $inf_codigo,
            "total_linhas"  => $texto["total_de_linha"]
        );

        return $lex_codigo;
    }

    private function p_reservada( $palavra, $linha, $cont_linha, $i )
    /* Identifica se a palavra analisada é reservada. */
    {
        $palavra = trim( $palavra );
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
        $palavra = trim( $palavra );
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
        $palavra = trim( $palavra );
        // array com símbolos e operadores
        $operador = array(
            "+",
            "-",
            "*",
            "/",
            "=",
            "++",
            "--",
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

        $simbolo = array(
            "{",
            "}",
            "(",
            ")",
            ",",
            ";",
            "."
        );
   
        foreach ( $operador as $op )
        {
            if ( strstr($palavra, $op) )
            {
                $inf_token = array(
                    "token"     => $palavra,
                    "n_linha"   => $cont_linha,
                    "n_palavra" => ($i+1),
                    "tipo"      => "operador"
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
        foreach ( $simbolo as $sim )
        {
            if ( strstr($palavra, $sim) )
            {
                $inf_token = array(
                    "token"     => $palavra,
                    "n_linha"   => $cont_linha,
                    "n_palavra" => ($i+1),
                    "tipo"      => "símbolo"
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

            $palavra = trim( $palavra );

            $inf_token = array(
                "token"     => $palavra,
                "n_linha"   => $cont_linha,
                "n_palavra" => ($i+1),
                "tipo"      => "PALAVRA NÃO IDENTIFICADA"
            );
            return $inf_token;
        }
        else {
            $inf_token = array(
                "token"     => $palavra,
                "n_linha"   => $cont_linha,
                "n_palavra" => ($i+1),
                "tipo"      => "ESPAÇO EM BRANCO"
            );
            return $inf_token;
        }
        return false;
    }



    public function analise_sintatica( $texto )
    /* Identifica erros digitados no código, erros de escritas. */
    {
        echo "<br> total de linhas = ".$texto["total_linhas"]."<br>",
        "Código = <br>";
        // print_r($texto["inf_codigo"]);

        // identificar os blocos de código
        echo "<br> ==================== <br>";
        
        // sempre tem que ter esta abertura de código
        // if ( $texto["inf_codigo"][0][0]["token"] == "vamosBeber" )
        // {
        //     echo "abertura correta";
        // }
        // else {
        //     echo "ERRO - Esperado palavra reservada 'vamosBeber'<br>",
        //     "Linha ".$texto["inf_codigo"][0][0]["n_linha"]." => ",
        //     $texto["inf_codigo"][0][0]["token"];
        // }

        $cont_bloco = 0;

        for ($i=0; $i < count( $texto["inf_codigo"] ); $i++) { 
            // print_r( $texto["inf_codigo"][$i]["token"] );

            // print_r($texto["inf_codigo"][$i]);

            $token = $texto["inf_codigo"][$i];
            echo $token["token"];

            // var_dump($token);

            // se for bloco, adiciona no contador
            if ( $token["token"] == "{" ) {
                $cont_bloco++;
            } elseif ( $token["token"] == "}" ) {
                $cont_bloco--;
            }

            // teste semantico
            // soltaCana PARÂMETRO | pinga PARÂMETRO
            if ( ($token["token"] === "soltaCana") || ($token["token"] === "pinga") )
            {
                $pos_token = $texto["inf_codigo"][$i+1];
                // if ( (!$this->p_string( $pos_token["token"], " ", $pos_token["n_palavra"], $pos_token["n_linha"] )) || (!$this->p_func_var( $pos_token["token"], " ", $pos_token["n_palavra"], $pos_token["n_linha"] )) )
                if ( !$this->p_func_var($pos_token["token"], " ", $pos_token["n_palavra"], $pos_token["n_linha"]) )
                {
                    echo "<br>*parâmetro inválido na linha ".$pos_token["n_linha"]."*";
                }
            }

            // 51 PARÂMETRO boaIdeia?
            elseif ( $token["token"] === "51" )
            {
                // primeiro elemento
                $primeira_palavra = $token["n_palavra"];

                // retorna o valor do elemento
                foreach ( $texto["inf_codigo"] as $key ) {
                    if ( $key["n_linha"] == $token["n_linha"] ) {
                        $ultima_palavra = $key["n_palavra"];
                    }
                }
                // retorna o penultimo elemento (boaIdeia?)
                foreach ( $texto["inf_codigo"] as $key ) {
                    if ( ($key["n_linha"] == $token["n_linha"]) && ($key["n_palavra"] == ($ultima_palavra-1)) ) {
                       $penultima_palavra = $key["token"];
                    }
                }
                // retorna o segundo elemento
                foreach ( $texto["inf_codigo"] as $key ) {
                    if ( ($key["n_linha"] == $token["n_linha"]) && ($key["n_palavra"] == ($primeira_palavra+1)) ) {
                        $parametro = $key["token"];
                    }
                }

                // testa validação de PARÂMETRO com operadores
                if ( (!$this->parametro_op($parametro, $texto["inf_codigo"], $token["n_linha"], $primeira_palavra)) || ( $penultima_palavra != "boaIdeia?" ) ) {
                    echo "<br>*parâmetro inválido na linha ".$token["n_linha"]."*";
                }
            }

            // soMaisUmaSe PARÂMETRO | transformandoDragaoEmPrincesaSe PARÂMETRO
            elseif ( ($token["token"] === "soMaisUmaSe") || ($token["token"] === "transformandoDragaoEmPrincesaSe") )
            {
                // primeiro elemento
                $primeira_palavra = $token["n_palavra"];

                // retorna o segundo elemento
                foreach ( $texto["inf_codigo"] as $key ) {
                    if ( ($key["n_linha"] == $token["n_linha"]) && ($key["n_palavra"] == ($primeira_palavra+1)) ) {
                        $parametro = $key["token"];
                    }
                }

                // testa validação de PARÂMETRO com operadores
                if ( !$this->parametro_op($parametro, $texto["inf_codigo"], $token["n_linha"], $primeira_palavra) ) {
                    echo "<br>*parâmetro inválido na linha ".$token["n_linha"]."*";
                }
            }

            // tropica / transformandoDragaoEmPrincesaSe PARÂMETRO

            // bebedeira PARÂMETRO , PARÂMETRO , PARÂMETRO
            elseif ( $token["token"] === "bebedeira" )
            {
                // primeiro elemento
                $primeira_palavra = $token["n_palavra"];

                // retorna o segundo elemento
                // 1° parametro
                foreach ( $texto["inf_codigo"] as $key ) { // bebedeira
                    if ( ($key["n_linha"] == $token["n_linha"]) && ($key["n_palavra"] == ($primeira_palavra+1)) ) {
                        $parametro = $key["token"];
                    }
                }
                // testa validação de PARÂMETRO com operadores
                if ( !$this->parametro_op($parametro, $texto["inf_codigo"], $token["n_linha"], $primeira_palavra) ) {
                    echo "<br>*parâmetro inválido na linha ".$token["n_linha"]."*";
                }
                
                // 2° parametro
                foreach ( $texto["inf_codigo"] as $key ) {
                    if ( ($key["n_linha"] == $token["n_linha"]) && ($key["n_palavra"] == ($primeira_palavra+6)) ) {
                        $parametro = $key["token"];
                    }
                }
                // testa validação de PARÂMETRO com operadores
                if ( !$this->parametro_op($parametro, $texto["inf_codigo"], $token["n_linha"], $primeira_palavra) ) {
                    echo "<br>*parâmetro inválido na linha ".$token["n_linha"]."*";
                }

                // 3° parametro
                // retorna o valor do elemento
                foreach ( $texto["inf_codigo"] as $key ) {
                    if ( $key["n_linha"] == $token["n_linha"] ) {
                        $ultima_palavra = $key["n_palavra"];
                    }
                }
                // retorna o penultimo elemento
                $som_teste = 0;
                foreach ( $texto["inf_codigo"] as $key ) {
                    if ( ($key["n_linha"] == $token["n_linha"]) && ($key["n_palavra"] == ($ultima_palavra-3)) ) {
                        $penultima_palavra = $key["token"];
                        if ( $penultima_palavra === "pinga" ) {
                            $som_teste = 1;
                            foreach ( $texto["inf_codigo"] as $key ) {
                                if ( ($key["n_linha"] == $token["n_linha"]) && ($key["n_palavra"] == ($ultima_palavra-2)) ) {
                                    $penultima_palavra = $key["token"];
                                    if ( preg_match("/[0-9a-zA-Z_]$/" , $penultima_palavra) ) {
                                        $som_teste = 2;
                                        foreach ( $texto["inf_codigo"] as $key ) {
                                            if ( ($key["n_linha"] == $token["n_linha"]) && ($key["n_palavra"] == ($ultima_palavra-1)) ) {
                                               $penultima_palavra = $key["token"];
                                                if ( ($penultima_palavra === "++") || ($penultima_palavra === "--") ) {
                                                    $som_teste = 3;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
                if ( $som_teste < 3 ) { // verifica se não chegou até o fim da verificação
                    echo "<br>*parâmetro inválido na linha ".$token["n_linha"]."*";
                }
            }
            
            // prepara PARÂMETRO | desceUma PARÂMETRO
            elseif ( ($token["token"] === "prepara") || ($token["token"] === "desceUma") )
            {
                // primeiro elemento
                $primeira_palavra = $token["n_palavra"];

                // retorna o segundo elemento
                foreach ( $texto["inf_codigo"] as $key ) {
                    if ( ($key["n_linha"] == $token["n_linha"]) && ($key["n_palavra"] == ($primeira_palavra+1)) ) {
                        $parametro = $key["token"];
                        if ( !preg_match("/[0-9a-zA-Z_]$/" , $parametro) ) { // valida prepara PARÂMETRO
                            echo "<br>*parâmetro inválido na linha ".$token["n_linha"]."*";
                        }
                    }
                }
            }

            // return deuPorHoje PARÂMETRO (pode ter ou não)

            echo "<br>----<br>";
        }


        // VERIFICAR ; | { | } EM CADA LINHA


        if ( $cont_bloco > 0 ) {
            echo "ABERTURA|FECHAMENTO DE BLOCO INCORRETO!";
        }

        // foreach ( $texto["inf_codigo"] as $linha ) {
        //     // print_r( $linha );
        //     echo $linha["token"];
        //     echo "<br>";
        //     // $linha["token"]."<br>";

        //     // print_r($linha);

        //     if ( $linha["token"] == "soltaCana" ) {
        //         # code...
        //     }

        // }

    }

    private function parametro_op( $parametro_1, $texto, $n_linha, $primeira_palavra ) {
        $operador = array( "+", "-", "*", "/", "=", "++", "--", "==", "<", ">", "<=", ">=", "!", "!=", "<>", "&&", "||" );
        // formato pinga VAR OP pinga VAR
        // formato pinga VAR OP string
        // formato pinga VAR OP numero
        if ( $parametro_1 === "pinga" ) { // valida PINGA
            foreach ( $texto as $key ) {
                if ( ($key["n_linha"] == $n_linha) && ($key["n_palavra"] == ($primeira_palavra+2)) ) {
                    $parametro_2 = $key["token"];
                    $parametro_2_np = $key["n_palavra"];
                }
            }
            if ( $this->p_func_var( $parametro_2, " ", $parametro_2_np, $n_linha )) { // valida pinga VARIAVEL
                // se for variavel testa se o próximo é um operador
                foreach ( $texto as $key ) {
                    if ( ($key["n_linha"] == $n_linha) && ($key["n_palavra"] == ($primeira_palavra+3)) ) {
                        $parametro_3 = $key["token"];
                    }
                }
                $op_ok = 0;
                foreach ( $operador as $op ) {
                    if ( $parametro_3 === $op ) {  // valida pinga variavel OPERADOR
                        $op_ok = 1;
                    }
                }
                // se validar operador pega o próximo elemento
                if ( $op_ok === 1 ) {
                    foreach ( $texto as $key ) {
                        if ( ($key["n_linha"] == $n_linha) && ($key["n_palavra"] == ($primeira_palavra+4)) ) {
                            $parametro_4 = $key["token"];
                        }
                    }
                    // se for operador testa se o próximo é uma variavel
                    if ( $parametro_4 === "pinga" ) { // valida pinga variavel operador PINGA
                        foreach ( $texto as $key ) {
                            if ( ($key["n_linha"] == $n_linha) && ($key["n_palavra"] == ($primeira_palavra+5)) ) {
                                $parametro_5 = $key["token"];
                                $parametro_5_np = $key["n_palavra"];
                            }
                        }
                        if ( $this->p_func_var( $parametro_5, " ", $parametro_5_np, $n_linha )) { // valida pinga variavel operador pinga VARIAVEL
                            // echo "<br>SUCESSO no formato pinga VAR OP pinga VAR";
                            return true;

                        }
                    }
                    // elseif ( $parametro_4 === STRING ) { // valida pinga variavel operador STRING
                    //     # code...
                    // }
                    elseif ( preg_match("/[0-9]$/" , $parametro_4) ) { // valida pinga variavel operador NUMERO
                        // verificar se valida numeros float
                        // echo "<br>SUCESSO no formato pinga VAR OP numero";
                        return true;

                    }
                }
            }
        }
        // falta fazer para STRING

        // formato numero OP pinga VAR
        // formato numero OP string
        // formato numero OP numero
        elseif ( preg_match("/[0-9]$/" , $parametro_1) ) { // valida NUMERO
            foreach ( $texto as $key ) {
                if ( ($key["n_linha"] == $n_linha) && ($key["n_palavra"] == ($primeira_palavra+2)) ) {
                    $parametro_2 = $key["token"];
                }
            }
            $op_ok = 0;
            foreach ( $operador as $op ) {
                if ( $parametro_2 === $op ) {  // valida numero OPERADOR
                    $op_ok = 1;
                }
            }
            // se validar operador pega o próximo elemento
            if ( $op_ok === 1 ) {
                foreach ( $texto as $key ) {
                    if ( ($key["n_linha"] == $n_linha) && ($key["n_palavra"] == ($primeira_palavra+3)) ) {
                        $parametro_3 = $key["token"];
                    }
                }
                // se for operador testa se o próximo é uma variavel
                if ( $parametro_3 === "pinga" ) { // valida numero operador PINGA
                    foreach ( $texto as $key ) {
                        if ( ($key["n_linha"] == $n_linha) && ($key["n_palavra"] == ($primeira_palavra+4)) ) {
                            $parametro_4 = $key["token"];
                            $parametro_4_np = $key["n_palavra"];
                        }
                    }
                    if ( $this->p_func_var( $parametro_4, " ", $parametro_4_np, $n_linha )) { // valida numero operador pinga VARIAVEL
                        // echo "<br>SUCESSO no formato numero OP pinga VAR";
                        return true;

                    }
                }
            }
        }
        
        // formato ! pinga VAR
        // formato ! string
        // formato ! numero
        elseif ( $parametro_1 === "!" ) { // valida !
            foreach ( $texto as $key ) {
                if ( ($key["n_linha"] == $n_linha) && ($key["n_palavra"] == ($primeira_palavra+2)) ) {
                    $parametro_2 = $key["token"];
                }
            }
            if ( $parametro_2 === "pinga" ) { // valida PINGA
                foreach ( $texto as $key ) {
                    if ( ($key["n_linha"] == $n_linha) && ($key["n_palavra"] == ($primeira_palavra+3)) ) {
                        $parametro_3 = $key["token"];
                        $parametro_3_np = $key["n_palavra"];
                    }
                }
                if ( $this->p_func_var( $parametro_3, " ", $parametro_3_np, $n_linha )) { // valida pinga VARIAVEL
                    // echo "<br>SUCESSO no formato ! pinga VAR";
                    return true;

                }
            }
            // falta fazer para validar ! STRING
            elseif ( preg_match("/[0-9]$/" , $parametro_2) ) { // valida NUMERO
                // echo "<br>SUCESSO no formato ! NUMERO";
                return true;

            }
        }

        return false;
    }

    public function traducao( $texto )
    {
        // exemplo:     str_replace('vamos beber', '<?php');
    }

}

$tradutor = new Tradutor();
$fonte_fragmentado = $tradutor->divide_texto( $codigo_fonte );
$fonte_lexico = $tradutor->analise_lexica( $fonte_fragmentado );
$fonte_sintatico = $tradutor->analise_sintatica( $fonte_lexico );