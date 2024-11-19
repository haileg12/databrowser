<!DOCTYPE html>
<html>

<head>
    <style>
        .box {
            width: 750px;
            padding: 20px;
            background-color: #fff;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-top: 100px;
        }
    </style>
</head>

<body>
	<div class="container box">
		<h3 align="center">
			One Piece JSON Into Database
        </h3><br />
		
		<?php
		$servername = "localhost"; // default server name
		$username = "Haile"; // user name that you created
		$password = "haile"; // password that you created
		$dbname = "projectdatabrowser";

		$connect = mysqli_connect("localhost", "Haile", "haile", "projectdatabrowser");

		$query = ' ';
		$table_data = ' ';

		$filename = "opdata.json";

		$data = file_get_contents($filename);

		$array = json_decode($data, true);

		foreach($array as $row) {
			"INSERT INTO onepiece VALUES 
                ('".$row["name"]."', '".$row["age"]."', 
                '".$row["gender"]."', '".$row["height"]."',
				'".$row["img"]."'); ";
			
			$table_data .= '
				<tr>
					<td>'.$row["name"].'</td>
					<td>'.$row["age"].'</td>
					<td>'.$row["gender"].'</td>
					<td>'.$row["height"].'</td>
					<td>'.$row["img"].'</td>
				</tr>
				';
		}

		echo ' 
			<table class="table table-bordered">
			<tr>
				<th width="45%">name</th>
				<th width="45%">age</th>
				<th width="45%">gender</th>
				<th width="45%">height</th>
				<th width="45%">img</th>
			</tr>
		';
		echo $table_data;
		echo '</table>';
?>
		<br/>
	</div>
</body>
</html>

