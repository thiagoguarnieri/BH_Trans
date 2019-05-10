<?php

////////////////////////////////////////////////////////////////////////////////
//passo 1 para gravar as linhas de onibus. Usado apenas uma vez.
function storeLinhas() {
    $link = "http://servicosbhtrans.pbh.gov.br/bhtrans/e-servicos/";
    $tmp = array();
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "busao";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    $doc = new DOMDocument();

    $query = "insert into linhas values";
    $page = file_get_contents("linhas.html");
    $doc->loadHTML($page);
    $itens = $doc->getElementsByTagName('li');

    for ($i = 0; $i < $itens->length; $i++) {
        $tmp = preg_split("/\-/", $itens->item($i)->textContent);
        $query .= "('" . trim($tmp[0]) . "','" . trim($tmp[1]) . "','" . $link . $itens->item($i)->firstChild->attributes->item(1)->nodeValue . "'),";
    }


    if (!mysqli_query($conn, rtrim($query, ","))) {
        echo "Erro: " . mysqli_error($conn);
        die();
    }

    mysqli_close($conn);
}

////////////////////////////////////////////////////////////////////////////////
//passo 2 para gravar as ruas univocamente
function storeRuas() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "busao";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    $urls = array();
    $doc = new DOMDocument();
    $ruas = array();

    //pegando todas as urls das linhas 
    $query = "select * from linhas";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $urls[] = $row['url'];
    }

    for ($k = 0; $k < count($urls); $k++) {
        echo "\n k = $k , $urls[$k]\n";
        $page = get_content($urls[$k]);
        $doc->loadHTML($page);
        $tabelas = $doc->getElementsByTagName('table');
        for ($j = 1; $j < $tabelas->length; $j++) {
            $ruas = $tabelas[$j]->getElementsByTagName('td');

            for ($i = 0; $i < $ruas->length; $i = $i + 2) {
                $nomeRua = str_replace("\xc2\xa0", ' ', trim($ruas->item($i)->textContent));
                $query = "select * from ruas where nome like '$nomeRua'";
                $result2 = $conn->query($query);

                //a rua ainda não existe
                if ($result2->num_rows == 0) {
                    $query = "insert into ruas (nome) values ('$nomeRua');";
                    if (!mysqli_query($conn, $query)) {
                        echo "Erro na inserção de rua: " . mysqli_error($conn);
                        die();
                    }
                }
            }
            $ruas = array();
        }
    }

    #var_dump($urls);
    mysqli_close($conn);
}

////////////////////////////////////////////////////////////////////////////////
function storeTrajetos() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "busao";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    $urls = array();
    $idsLinhas = array();
    $doc = new DOMDocument();
    $ruas = array();

    //pegando todas as urls das linhas 
    $query = "select * from linhas";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $urls[] = $row['url'];
        $idsLinhas[] = $row['id'];
    }

    ///////////////////////////////////
    //adicionando
    $countTraj = 1;
    $countEtapa = 1;
    //selecionando linha
    for ($k = 0; $k < count($urls); $k++) {
        echo "\n k = $k , $urls[$k]\n";
        $page = get_content($urls[$k]);
        $doc->loadHTML($page);
        $tabelas = $doc->getElementsByTagName('table');
        $countTraj = 1;

        //inserindo o trajeto
        for ($j = 1; $j < $tabelas->length; $j++) {
            $ruas = $tabelas[$j]->getElementsByTagName('td');
            $countEtapa = 1;

            //inserindo a etapa
            for ($i = 0; $i < $ruas->length; $i = $i + 2) {
                $nomeRua = str_replace("\xc2\xa0", ' ', trim($ruas->item($i)->textContent));
                $query = "select * from ruas where nome like '$nomeRua'";
                $result2 = $conn->query($query);

                //a rua existe e estamos pegando o id dela
                if ($result2->num_rows > 0) {
                    $row2 = $result2->fetch_assoc();
                    $query = "insert into trajetos values ('$idsLinhas[$k]',$countTraj,$countEtapa,$row2[id]);";
                    if (!mysqli_query($conn, $query)) {
                        echo "Erro na inserção da etapa do trajeto: " . mysqli_error($conn);
                        die();
                    }
                    $countEtapa++;
                }
            }
            $ruas = array();
            $countTraj++;
        }
    }

    #var_dump($urls);
    mysqli_close($conn);
}

////////////////////////////////////////////////////////////////////////////////
function storeGraph(){
 $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "busao";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    $linhas = array();
    $path = array();
    $graphRaw = array();
    $graph = array();

    //pegando todas as urls das linhas 
    $query = "select * from trajetos";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $linhas[$row['idlinha']][$row['idtrajeto']][$row['idEtapa']] = $row['idrua'];
    }

    //montando arestas
    foreach ($linhas as $id => $lin) {
        foreach ($lin as $traj) {
            foreach ($traj as $etp) {
                $path[] = $etp;
            }
            //montando trajeto
            for ($i = 0; $i < count($path) - 1; $i++) {
				if($path[$i] != $path[$i+1]){
					$graphRaw[$id][] = $path[$i] . "," . $path[$i + 1];
				}
            }
            $path = array();
        }
    }
    
    //deixando cada aresta uma vez só
	foreach($graphRaw as $id => $lin){
		foreach($lin as $edg){
			$graph[$id][$edg] = 1;
		}
	} 
    
    foreach($graph as $k1 => $ln){
		foreach($ln as $k2 => $ed){
			echo("$k1,'$k2'\n");
		}
	}
    
    #var_dump($graph);
}

////////////////////////////////////////////////////////////////////////////////
function makeGraph() {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "busao";
    $conn = mysqli_connect($servername, $username, $password, $dbname);
    $linhas = array();
    $path = array();
    $graphRaw = array();
    $graph = array();

    //pegando todas as urls das linhas 
    $query = "select * from trajetos inner join linhas_regioes on trajetos.idlinha = linhas_regioes.id where (reg1 = 2 or reg2 = 2 or reg3 = 2)";
    $result = $conn->query($query);
    while ($row = $result->fetch_assoc()) {
        $linhas[$row['idlinha']][$row['idtrajeto']][$row['idEtapa']] = $row['idrua'];
    }

    //montando arestas
    foreach ($linhas as $lin) {
        foreach ($lin as $traj) {
            foreach ($traj as $etp) {
                $path[] = $etp;
            }
            //montando trajeto
            for ($i = 0; $i < count($path) - 1; $i++) {
				if($path[$i] != $path[$i+1]){
					$graphRaw[] = $path[$i] . "," . $path[$i + 1];
				}
            }
            $path = array();
        }
    }

    //contando pesos
    for ($j = 0; $j < count($graphRaw); $j++) {
        if (isset($graph[$graphRaw[$j]])) {
            $graph[$graphRaw[$j]] += 1;
        }else{
            $graph[$graphRaw[$j]] = 1;
        }
    }
    
    //imprimindo
    #echo "v1,v2,weight\n";
    echo "v1,v2\n";
    foreach($graph as $ind => $val){
        #echo $ind.",".$val."\n";
        echo $ind."\n";
    }
    
    #var_dump($graph);
}

////////////////////////////////////////////////////////////////////////////////
//funcao para baixar página
function get_content($URL) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_URL, $URL);
    $data = curl_exec($ch);
    curl_close($ch);
    return $data;
}

makeGraph();
