<?php
    #<---- RETORNO DO JSON COMO OBJETO ---->

    $arc_json=file_get_contents("cnv.json");

    $dc=json_decode($arc_json);
    $pessoa=$dc->pessoa;
    $pessoas=$dc->pessoas;

    foreach ($pessoa as $key => $psn) {
        echo "-Nome: $psn->nome \n-Idade: $psn->idade \n-Altura: $psn->altura \n";
        echo "__________________\n";
    }
    foreach ($pessoas as $key => $psns) {
        echo " -Nome: $psns->nome \n -Idade: $psns->idade \n -Sexo: $psns->sexo \n";
        echo "___________________\n";
    }

    $person1 = ["nome"=>"Pedro","idade"=>16,"sexo"=>"masculino"];
    $person2=["nome"=>"Arutha","idade"=>19,"sexo"=>"masculino"];
    $person3=["nome"=>"Ara","idade"=>14,"sexo"=>"feminino"];

    $personas = [$person1,$person2,$person3];
    $id=["pessoas"=>$personas];
    $prs_json=json_encode($id);
    $arc=fopen("cnv.json","a+");
    fwrite($arc,$prs_json);
    fclose($arc);
    echo "Quantas pessoas deseja add?\n";
    $qtd = trim(fgets(STDIN));
    $people=[];
    $psn="pessoa";
    for ($i=1; $i <$qtd+1 ; $i++) {
        echo "Escreva o nome da $i ° pessoa\n";
        $people[$psn.$i]["nome"]=trim(fgets(STDIN));
        echo "Diga a idade da $i ° pessoa\n";
        $people[$psn.$i]["idade"]=(int)trim(fgets(STDIN));
        echo "Qual é a altura da $i ° pessoa, Ex.: 1.89\n";
        $people[$psn.$i]["altura"]=trim(fgets(STDIN));
    }
    $id=["people"=>$people];
    file_put_contents("cnv.json",json_encode($id, JSON_PRETTY_PRINT));
    #<--- FIM DE OBJETOS --->


    #FUNCAO PARA INSERIR PESSOA
    function insere($nome,$idade,$sexo){
        $lista=json_decode(file_get_contents("cnv.json"),true);
        $lista[]=[
            "nome"=>$nome,
            "idade"=>$idade,
            "sexo"=>$sexo
        ];
        file_put_contents("cnv.json",json_encode($lista,JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_NUMERIC_CHECK));
    }
    echo "Inserir novo contato:\n";
    echo "Nome:\n";
    $nome=trim(fgets(STDIN));
    echo "Idade:\n";
    $idade=(int)trim(fgets(STDIN));
    echo "Sexo:\n";
    $sexo=trim(fgets(STDIN));
    insere($nome,$idade,$sexo);


    #FUNCAO TROCA DE VALOR
    function trocaValor($atributo,$who,$newAtt){
        $lista=json_decode(file_get_contents("cnv.json"),true);
        foreach ($lista as $chave => $valor) {
            if ($valor["nome"]==$who) {
                $lista[$chave][$atributo]=$newAtt;
            }
        }
        return $lista;
    }
    #<-- RECEBE O ATRIBUTO, QM QUER MUDAR E QUAL O NOVO ATRIBUTO -->
    echo "Quem voce quer mudar:\n";
    $who=trim(fgets(STDIN));
    echo "O que quer mudar?\n";
    $atributo = trim(fgets(STDIN));
    echo "Qual o $atributo novo ?\n";
    $newAtt = trim(fgets(STDIN));
    $list=trocaValor($atributo,$who,$newAtt);
    file_put_contents("cnv.json",json_encode($list,JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK));

    # LISTAR TODOS USUARIOS
    function lista(){
        $lista=json_decode(file_get_contents("cnv.json"),true);
        foreach ($lista as $pessoa) {
            $pessoas[]=$pessoa;
        }
        return $pessoas;
    }
    $list=lista();
    echo "____________________________________________________________________________\n";
    foreach ($list as $pessoa => $attr) {
        echo "Nome:".$attr["nome"]."\n"."Idade:".$attr["idade"]."\n"."Sexo:".$attr["sexo"]."\n";
        echo "____________________________________________________________________________\n";
    }

    # EXCLUIR UM USUARIO PELA ID
    function exclui($id){
        $lista=json_decode(file_get_contents("cnv.json"),true);
        foreach ($lista as $key => $value) {
            if($value["id"]==$id){
                //unset($lista[$key]);
                array_splice($lista,$key,1);
                break;
            }
        }
        file_put_contents("cnv.json",json_encode($lista,JSON_PRETTY_PRINT | JSON_NUMERIC_CHECK));
    }
    echo "De a id para excluir:\n";
    $id=(int)trim(fgets(STDIN));
    exclui($id);

    # PESQUISAR USUARIO PELO NOME
    function search($input){
        $json=json_decode(strtolower(file_get_contents("cnv.json")),true);
        $input=strtolower($input);
        $aux=0;
        foreach ($json as $key => $value) {
            $nome=explode(" ",$value["nome"]);
            for ($i=0; $i <sizeof($nome); $i++) {
                if($nome[$i]==$input){
                    $result[]=$value;
                }
            }
            if(isset($result[$aux])){
                $result[$aux]["nome"]=ucwords($result[$aux]["nome"]);
                $aux++;
            }
        }
        if (isset($result)) {
            asort($result);
            return $result;
        }else {
            $result=[];
            return $result;
        }
    }
    echo "Quem você quer achar?\n";
    $input=(string)trim(fgets(STDIN));

    $resultado=search($input);

    foreach ($resultado as $key => $value) {
        echo "Nome: ".$value["nome"]."\n Idade: ".$value["idade"]."\n Sexo: ".$value["sexo"]."\n";
        echo "_____________________________________\n";
    }
?>
