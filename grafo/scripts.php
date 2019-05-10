<?php 
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "cep";
$conn = mysqli_connect($servername, $username, $password, $dbname); 

$file = fopen("ruas.csv","r");

while(!feof($file))
{
	$rua = fgetcsv($file);
	$sql = "select logradouro,bairro from cep_mg where logradouro like '%$rua[1]%'";
	$result = mysqli_query($conn, $sql);
	
	echo "\n".$rua[1]."\n";
	if (mysqli_num_rows($result) > 0) {
		while($row = mysqli_fetch_assoc($result)) {
			echo $row["logradouro"]. "," . $row["bairro"]. "\n";
		} 
	}else{
		echo "sem correlacao\n";
	}
}

fclose($file);
