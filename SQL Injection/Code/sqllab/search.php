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
		
		/* Injectable SQL function to find user details */
		function showSearchResults(){
			echo '<div id="results">';	
			
			//create a link mysql db
			$link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());
			
			//create a query
			$query = "SELECT ID,username FROM member WHERE ID = ".$_GET['id'];
			
			//execute query 
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
		
		/* Function to find user details with mysqli_real_escape_string added. Still Injectable though !!! */
		function showSearchResultsWithEscapeChars()
		{
			echo '<div id="results">';
			
			echo '</br><h1>Test  with Escape Strings </h1></br>';
			
			$link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());
			
			//Escape the $_GET session variable
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
		
		/* function which uses Prepared Statement */
		function showSearchResultsPreparedStmt()
		{
			echo '<div id="results">';
			
			echo '</br><h1>Test  with Prepared Statements </h1></br>';
			
			$link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());
			
			// Make sure you escape the variable
			$ID = $_GET['id'];
			
			// prepare a SQL statement that retrives ID and username from the DB 
			// according to ID received from $_GET['id']
			// Remove the one and enter the correct statement. 
			if(
				//$stmt = NULL
				1
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
		
		/* function to find user details using Stored Procedure */
		function showSearchResultStoredProcedure()
		{
			echo '<div id="results">';
			
			echo '</br><h1>Test  with Stored Procedure </h1></br>';
			
			try {
				$conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
				// execute the stored procedure
				
				// Call the stored procedure that you created and retrieve the ID and username
				$sql = NULL;
				
				// Create a prepared statement using the $sql
				$stmt = NULL;
				
				//bind :inputID with id from GET variable
				$stmt->bindParam(':inputID', $_GET['id'], PDO::PARAM_INT);
				//execute the query
				$stmt->execute();
				//close the cursor
				$stmt->closeCursor();
				// execute the second query to get customer's level
				$r = $conn->query("SELECT @resID AS ID,@uname AS username")->fetch(PDO::FETCH_ASSOC);

				if (isset($r['ID'])) {
						echo 
					$r['ID'], '==>',  
					$r['username'],  
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
