<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>SQL Injection Test 2</title>
		<link rel="stylesheet" href="bootstrap.min.css">
		<link rel="stylesheet" href="bootstrap-theme.min.css">
		<script src="bootstrap.min.js"></script>
		<script src="jquery.min.js"></script>
		<link rel="stylesheet" href="styles.css">
	</head>
<body>
<div class="container">
	<div class="row">
	<div class="col-sm-6 col-md-4 col-md-offset-4">
	<h1 class="text-center login-title">Search User</h1>
	<div class="account-wall">
	
	<img class="profile-img" src="searchppl.png" alt="" width="350" height="350">
	<br/>
	<br/>
	<form class="form-signin" method="GET" action="search.php">
		<br/>
		<br/>		
		<label class="pull-left" >
			<h3>Enter UserID to Search :</h3>
		</label>
		<br/>
		<input type="text" id="id" name="id" class="form-control" placeholder="UserID" required autofocus>
		<br/>
		
		<button class="btn btn-lg btn-primary btn-block" type="submit" >
			Search
		</button>
		
		
	</form>
	<?php
		
		/* Define Database Host ==> localhost in our case */
		define('DB_HOST', 'localhost');
		/* Define Database Name ==> sqltest is name of MySQL DB we want to connect to */
		define('DB_NAME', 'sqltest');
		/* Define Database UserName ==> 'root' as Username for Logging into MySQL */
		define('DB_USER','root');
		/* Define Database Password ==> 'password' as Password for Logging into MySQL */
		define('DB_PASSWORD','password');

		
		function showSearchResults(){
			echo '<div id="results">';	
	
			$link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());
			
			$query = "SELECT ID,username FROM member WHERE ID = ".$_GET['id'];
			
			$result = mysqli_query($link,$query);  
			
			echo $query;
			
			echo "<br/> \n Search results!!! \n <br/>";
			while( false!=($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) ) {  
				echo 
					$row['ID'], '==>',  
					$row['username'],  
					"<br />\n";  
			}
			echo '</div>';
			/* free result set */
			mysqli_free_result($result);

			/* close connection */
			mysqli_close($link);
		}

		function showSearchResultsWithEscapeChars()
		{
			echo '<div id="results">';
			
			echo '</br><h1>Test  with Escape Strings </h1></br>';
			
			$link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());

			$ID = mysqli_real_escape_string($link,$_GET['id']) ;

			$result = mysqli_query($link,"SELECT ID,username FROM member WHERE ID = ".$ID);

			echo "<br/> \n Search results!!! \n <br/>";
			while( false!=($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) ) {  
				echo 
					$row['ID'], '==>',  
					$row['username'],  
					"<br />\n";  
			}
			echo '</div>';
			/* free result set */
			mysqli_free_result($result);

			/* close connection */
			mysqli_close($link);
		}

		function showSearchResultsPreparedStmt()
		{
			echo '<div id="results">';
			
			echo '</br><h1>Test  with Prepared Statements </h1></br>';
			
			$link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());
			
			// Make sure you escape the variable
			$ID = $_GET['id']; 
			
			//$ID = mysqli_real_escape_string($link,$_GET['id']);

			if(
				// prepare a SQL statement that retrives ID and username from the DB 
				// according to ID received from $_GET['id']
				//$stmt = 
				//$stmt = mysqli_prepare($link,"SELECT ID,username FROM member WHERE ID = ?")
			){

				/* bind parameters for markers */
				mysqli_stmt_bind_param($stmt, "i", $ID);

				/* execute query */
				mysqli_stmt_execute($stmt);

				/* bind result variables */
				mysqli_stmt_bind_result($stmt, $result_id,$result_username);

				/* fetch value */
				mysqli_stmt_fetch($stmt);

				echo 
				$result_id, '==>',  
				$result_username,  
				"<br />\n";

				/* close statement */	
				mysqli_stmt_close($stmt);
			}
			/* close connection */
			mysqli_close($link);	
			
			echo '</div>';
		}

		function showSearchResultStoredProcedure()
		{
			echo '<div id="results">';
			
			echo '</br><h1>Test  with Stored Procedure </h1></br>';
			
			try {
				$conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
				// execute the stored procedure
				$sql = 'CALL sp_getUser(:inputID,@resID,@uname)';
				$stmt = $conn->prepare($sql);

				$stmt->bindParam(':inputID', $_GET['id'], PDO::PARAM_INT);
				$stmt->execute();
				$stmt->closeCursor();
				// execute the second query to get customer's level
				$record = $conn->query("SELECT @resID AS ID,@uname AS username")->fetch(PDO::FETCH_ASSOC);

				if (isset($record['ID'])) {
						echo 
					$record['ID'], '==>',  
					$record['username'],  
					"<br />\n";
				}
				else
				{
					echo "No User found !!!";
				}
			} catch (PDOException $pe) {
				die("Error occurred:" . $pe->getMessage());
			}
			
			echo '</div>';
			
		}

		if(isset($_GET['id'])){	
			showSearchResults();
			//showSearchResultsWithEscapeChars();
			//showSearchResultsPreparedStmt();
			//showSearchResultStoredProcedure();
		}
	?>
	</div>

</div>
</div>
</div> 
</body>
</html>
