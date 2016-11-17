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
        echo "<br>Total de linhas analisadas no código = ".$texto["total_linhas"]."<br>";
        // "Código = <br>";
        // "<br> ============================================================ <br>";
        // print_r($texto["inf_codigo"]);

        $cont_bloco = 0;
        // numero da linha do segundo elemento, se for 1, significa que tem vamosBeber 'alguma coisa', o que não pode
        $prox_elemento = $texto["inf_codigo"][1]["n_linha"];
        // sempre tem que ter esta abertura de código na primeira linha
        if ( ($texto["inf_codigo"][0]["token"] === "vamosBeber") && ($prox_elemento != 1) )
        {
            for ($i=0; $i < count( $texto["inf_codigo"] ); $i++) { 

                $token = $texto["inf_codigo"][$i];
                $token["token"];

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

                        return false;
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

                        return false;
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

                        return false;
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

                        return false;
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

                        return false;
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

                        return false;
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

                                return false;
                            }
                        }
                    }
                }

                // return deuPorHoje PARÂMETRO (pode ter ou não)
                // echo "<br>----<br>";
            }
        } else {
            echo "<br>*parâmetro inválido na linha ".$texto["inf_codigo"][0]["n_linha"]."*";

            return false;
        }

        // VERIFICAR ; | { | } EM CADA LINHA        NÃO PODE TER ;{ OU ;} OU ;; OU }} OU {{ - TEM QUE TER UM ESPAÇO{
        $fim_linha = $this->an_fim_linha( $texto );
        // var_dump($fim_linha);

        if ( $fim_linha["valida"] === 1 )
        {
            echo "<br>*parâmetro inválido na linha ".$fim_linha["linha"]."*";

            return false;
        }

        // verifica se há blocos abertos e não fechados
        if ( ($cont_bloco > 0) || ($cont_bloco < 0) ) {
            echo "ABERTURA|FECHAMENTO DE BLOCO INCORRETO!";

            return false;
        }

        return $texto;
    }

    private function an_fim_linha( $texto )
    /* verifica o fim de cada linha, se é válido. Se inválido, retorna a linha da ocorrência */
    {
        // verifica se o fim de linha ; | { | }
        // retorna o valor do ultimo elemento
        for ($x=0; $x < $texto["total_linhas"]; $x++) { 
            foreach ( $texto["inf_codigo"] as $key ) {
                if ( $key["n_linha"] == $x ) {
                    $ultima_palavra[$x] = $key["n_palavra"];
                }
            }
        }
        // if ( isset($ultima_palavra) ) {
        //     echo "<br>";
        //     print_r($ultima_palavra);
        //     echo "<br>";
        // }
        // x=2 pois pula a primeira linha, que deve ser vamosBeber
        for ($x=2; $x < $texto["total_linhas"]; $x++) { 
            foreach ( $texto["inf_codigo"] as $key ) {
                if ( ($key["n_linha"] === $x) && ($key["n_palavra"] === $ultima_palavra[$x] ) ) {
                    $u_palavra = $key["token"];
                    // echo "FIM ".$u_palavra."<br>";
                    if ( $u_palavra != "{" ) {
                        if ( $u_palavra != "}" ) {
                            if ( $u_palavra != ";" ) {
                                if ( trim($u_palavra) != false ) {
                                    // erro
                                    $linha = array(
                                        "valida"    => 1,
                                        "linha"     => $key["n_linha"]
                                    );
                                        
                                    return $linha;
                                }
                            }
                        }
                    }
                }
            }
        }

        // verifica onde há as ocorrências
        $teste_linha = 1;
        foreach ( $texto["inf_codigo"] as $key ) {
            if ( $key["n_linha"] === $teste_linha ) {
                $num_palavra[ $teste_linha ] = $key["n_palavra"];
            } else { $teste_linha++; }
        }
        // os indices são as linhas
        $ind_palavra = array_keys( $num_palavra );
        // elimina a linha 1, pois deve ser vamosBeber, que não exige;
        
        $cont_num = 0;
        foreach ( $num_palavra as $n ) {
            $_num_palavra[ $cont_num ] = $n;
            $cont_num++;
        }
       
        // verifica o ultimo de cada linha
        for ($i=1; $i < count( $_num_palavra ); $i++) { 
            $ind_palavra[$i]; // linha da ocorrencia
            $_num_palavra[$i]; // posição da ocorrencia

            // retorna o penultimo elemento, aqui ocorre a validação para ;; | ;{ | ;} | etc
            foreach ( $texto["inf_codigo"] as $key ) {
                if ( ($key["n_linha"] == $ind_palavra[$i]) && ($key["n_palavra"] == ($_num_palavra[$i]-1)) ) {
                    $penultima_palavra = $key["token"];
                    if ( ($penultima_palavra === ";") || ($penultima_palavra === "{") || ($penultima_palavra === "}") ) {
                        // erro
                        $linha = array(
                            "valida"    => 1,
                            "linha"     => $key["n_linha"]
                        );
                            
                        return $linha;
                    }
                }
            }
        }

        // valida ; { }
        for ($i=0; $i < count( $texto["inf_codigo"] ); $i++) { 

            $token = $texto["inf_codigo"][$i];
            
            // ;
            if ( $token["token"] === ";" )
            {
                // primeiro elemento
                $primeira_palavra = $token["n_palavra"];

                // retorna o valor do elemento
                foreach ( $texto["inf_codigo"] as $key ) {
                    if ( $key["n_linha"] == $token["n_linha"] ) {
                        $n_palavra = $key["n_palavra"];
                    }
                }
                // retorna o penultimo elemento
                foreach ( $texto["inf_codigo"] as $key ) {
                    if ( ($key["n_linha"] == $token["n_linha"]) && ($key["n_palavra"] == ($n_palavra-1)) ) {
                        $penultima_palavra = $key["token"];
                        $key["n_linha"];
                        if ( ($penultima_palavra === ";") || ($penultima_palavra === "{") || ($penultima_palavra === "}") ) {
                            // erro
                            $linha = array(
                                "valida"    => 1,
                                "linha"     => $key["n_linha"]
                            );
                            
                            return $linha;
                        }
                    }
                }
            }

            // {
            if ( $token["token"] === "{" )
            {
                // primeiro elemento
                $primeira_palavra = $token["n_palavra"];

                // retorna o valor do elemento
                foreach ( $texto["inf_codigo"] as $key ) {
                    if ( $key["n_linha"] == $token["n_linha"] ) {
                        $n_palavra = $key["n_palavra"];
                    }
                }
                // retorna o penultimo elemento
                foreach ( $texto["inf_codigo"] as $key ) {
                    if ( ($key["n_linha"] == $token["n_linha"]) && ($key["n_palavra"] == ($n_palavra-1)) ) {
                        $penultima_palavra = $key["token"];
                        $key["n_linha"];
                        if ( ($penultima_palavra === ";") || ($penultima_palavra === "{") || ($penultima_palavra === "}") ) {
                            // erro
                            $linha = array(
                                "valida"    => 1,
                                "linha"     => $key["n_linha"]
                            );
                            
                            return $linha;
                        }
                    }
                }
            }

            // }
            elseif ( $token["token"] === "}" )
            {
                // primeiro elemento
                $primeira_palavra = $token["n_palavra"];

                // retorna o valor do elemento
                foreach ( $texto["inf_codigo"] as $key ) {
                    if ( $key["n_linha"] == $token["n_linha"] ) {
                        $n_palavra = $key["n_palavra"];
                        if ( $n_palavra > 1 ) {
                            // erro
                            $linha = array(
                                "valida"    => 1,
                                "linha"     => $key["n_linha"]
                            );
                            
                            return $linha;
                        }
                    }
                }
            }
        }
        
        // sucesso
        $linha = array(
            "valida"    => 0,
            "linha"     => 0
        );

        return $linha;
    }

    private function parametro_op( $parametro_1, $texto, $n_linha, $primeira_palavra )
    /* verifica se o parametro está no formato correto */
    {
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
    /* realiza a tradução e também a validação para linguagem alvo */
    {
        try {
            $codigo_traduzido = null;
            $linha_impressa = 1;

            $q_letras = count($texto["inf_codigo"]); // quantidades de elementos
            $q_linhas = $texto["total_linhas"]; // quantidades de linhas

            // imprime o código compilado para traduzir
            foreach ( $texto["inf_codigo"] as $key ) {
                if ( $key["n_linha"] > $linha_impressa ) {
                    echo "<br>";
                    $linha_impressa++;
                    // $codigo_traduzido .= "<br>"; // para definir quebra de linha
                }
                echo $key["token"]. " ";
                $palavra = $key["token"];
            }

            // realiza a tradução por palavra
            for ($i=0; $i < $q_letras; $i++) {
                $palavra = $texto["inf_codigo"][$i]["token"];
                $palavra = $this->substitui( $palavra );
                $palavra_traduzida[$i] = $palavra;
            }

            for ($i=0; $i < $q_letras; $i++) {
                // realiza a validação
                if ( ($i > 1) && ($i < $q_letras) ) {

                    // valida $VARIAVEL
                    $palavra_traduzida_anterior = $palavra_traduzida[($i-1)];
                    if ( $palavra_traduzida_anterior === "$") {
                        $palavra_traduzida[$i] = $palavra_traduzida_anterior.$palavra_traduzida[$i];
                        $palavra_traduzida[($i-1)] = "";
                    }

                    // valida while( <= 10 ){
                    if ( $palavra_traduzida_anterior === "while(") {
                        $pos_while = $i;
                        $fim_while = $q_letras;
                        for ($x = $pos_while; $x < $fim_while; $x++) { 
                            if ( $palavra_traduzida[$x] === "{" ) {
                                $palavra_traduzida[$x] = "){";

                                $fim_while = $pos_while;
                            }
                        }
                    }

                    // valida for( $i=0 ; $i < ; $i ++ ){
                    if ( $palavra_traduzida_anterior === "for(") {
                        $pos_for = $i;
                        $fim_for = $q_letras;
                        $q_virgula = 0;
                        for ($x = $pos_for; $x < $fim_for; $x++) { 
                            if ( $palavra_traduzida[$x] === "{" ) {
                                $palavra_traduzida[$x] = "){";
                                $fim_for = $pos_for;
                            }
                            elseif ( $palavra_traduzida[$x] === "," ) {
                                if ( $q_virgula < 2 ) {
                                    $palavra_traduzida[$x] = ";";
                                    $q_virgula++;
                                }
                            }
                        }
                    }
                }
            }

            // imprime código traduzido
            echo "<br>";
            echo "<br> ============================================================ <br>";
            // se existe um arquivo criado, exclui
            unlink( "traduzido.php" );
            for ($i=0; $i < $q_letras; $i++) {
                echo "<input type='text' value='".$palavra_traduzida[$i]."' />";

                $fp = fopen( "traduzido.php", "a" );
                $escreve = fwrite($fp, $palavra_traduzida[$i]." ");
                fclose($fp);
            }

            // ajustar
            // echo "<textarea name='conteudo' rows='25' cols='100'>".$fp."</textarea>";

            return true;
        } catch (Exception $e) {
            echo 'Ocorreu um erro ao realizar a tradução do código: ',  $e->getMessage(), "\n";
        }
    }

    private function substitui( $palavra )
    // realiza a substituição de acordo com a linguagem alvo: PHP
    {
        trim($palavra);

        if ($palavra === "vamosBeber") {
            $palavra = "<?php";
        }

        if ($palavra === "acabouBebida") {
            $palavra = "?>";
        }
        if ($palavra === "soltaCana") {
            $palavra = "echo";
        }
        if ($palavra === "51") {
            $palavra = "if(";
        }
        if ($palavra === "boaIdeia?") {
            $palavra = ")";
        }
        if ($palavra === "bavaria!") {
            $palavra = "else";
        }
        if ($palavra === "pinga") {
            $palavra = "$";
        }
        if ($palavra === "transformandoDragaoEmPrincesaSe") {
            $palavra = "while(";
        }
        if ($palavra === "tropica") {
            $palavra = "do";
        }
        if ($palavra === "bebedeira") {
            $palavra = "for(";
            // ******* ajustar ',' e FECHAMENTO DE ')'
        }
        if ($palavra === "prepara") {
            $palavra = "function";
        }
        if ($palavra === "deuPorHoje") {
            $palavra = "return";
        }
        if ($palavra === "desceUma") {
            $palavra = " ";
        }

        return $palavra;
    }

}

$tradutor = new Tradutor();
$fonte_fragmentado = $tradutor->divide_texto( $codigo_fonte );

if ( $fonte_fragmentado != false )
{
    $fonte_lexico = $tradutor->analise_lexica( $fonte_fragmentado );

    if ( $fonte_lexico != false )
    {
        $fonte_sintatico = $tradutor->analise_sintatica( $fonte_lexico );
    } else {
        echo "<br>ERRO ENCONTRADO, CÓDIGO NÃO COMPILADO!";
    }
        if ( $fonte_sintatico != false )
        {
            echo "<br> ============================================================ <br>",
            "<b>SUCESSO AO COMPILAR!</b>",
            "<br><a href='traduzido.php' target='_blank' >Clique aqui para executar arquivo TRADUZIDO!</a>",
            "<br> ============================================================ <br>";

            $fonte_traduzido = $tradutor->traducao( $fonte_sintatico );

            if ( $fonte_traduzido != false ) {
                echo "<br> ============================================================ <br>";
                echo "<b>SUCESSO AO TRADUZIR!</b>";
                echo "<br> ============================================================ <br>";
            } else {
                echo "<br>ERRO ENCONTRADO, CÓDIGO NÃO TRADUZIDO!";
            }
        } else {
            echo "<br>ERRO ENCONTRADO, CÓDIGO NÃO COMPILADO!";
        }
} else {
    echo "<br>ERRO ENCONTRADO, CÓDIGO NÃO COMPILADO!";
}