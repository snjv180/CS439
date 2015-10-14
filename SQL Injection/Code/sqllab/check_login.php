<?php

/*
ShortCut Key
Zoom In : Ctrl + Shift + '+' 
Zoom Out : Ctrl + '-'
*/

/* Define Database Host ==> localhost in our case */
define('DB_HOST', 'localhost');
/* Define Database Name ==> sqltest is name of MySQL DB we want to connect to */
define('DB_NAME', 'sqltest');
/* Define Database UserName ==> 'root' as Username for Logging into MySQL */
define('DB_USER','root');
/* Define Database Password ==> 'password' as Password for Logging into MySQL */
define('DB_PASSWORD','password');



/* 

Function to SignIn to the Application and if authenticated redirected to 'welcome.php'
otherwise shown Authentication Failure. Has no security features so SQL Injection possible.
*/
function SignIn()
{
session_start();   //starting the session for user profile page
	if(!empty($_POST['username']))   //checking the 'user' name which is from index.html, is it empty or have some text
	{
		$username = $_POST['username'] ; //Extract username from POST variable submitted through form
		$password = md5($_POST['password']); //Extract password from POST variable submitted through form and generate a md5 hash of it

		/* Create a connection to MySQL */
		$link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error()); 
		
		/* Query the database and put the result Set in $result */
		$result = mysqli_query($link,"SELECT *  FROM member where username = '$username' AND password = '$password'");
		/* Get an associative array of the result Set */
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		/* If result is not empty then we have the correct user */
		if(!empty($row['username']) AND !empty($row['password']))
		{
			/* Store Username in session variable */
			$_SESSION['username']=$row['username'];
			echo "User Successfully Logged In!!!";
			/* relocate user to custom welcome page */
			header("Location: welcome.php");

		}
		else
		{
			/* Failed to Login Case */
			echo "Sorry... Login Failure!!!";
		}
		/* free result set */
		mysqli_free_result($result);

		/* close connection */
		mysqli_close($link);
	}
	else{
		/* Username and Password not available case */
		echo "Login Failure. Please Enter Username and Password";
	}
}

/* Function with only mysqli_real_escape_string implemented. Still susceptible to SQL Injection in special cases */
function SignInWithEscapeChars()
{
session_start();   //starting the session for user profile page
	if(!empty($_POST['username']))   //checking the 'user' name which is from Sign-In.html, is it empty or have some text
	{
		
		// create link to db
		$link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());
		
		//escape username
		$username = mysqli_real_escape_string($link,$_POST['username']) ;
		//escape password
		$password = mysqli_real_escape_string($link,md5($_POST['password']));
		
		//query db for result and store in $result
		$result = mysqli_query($link,"SELECT *  FROM member where username = '$username' AND password = '$password'");
		//get an associative array as result
		$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
		
		//check if we had a user matcing credentials
		if(!empty($row['username']) AND !empty($row['password']))
		{
			//store username in session variable
			$_SESSION['username']=$row['username'];
			echo "User Successfully Logged In!!!";
			//relocate to custom user page
			header("Location: welcome.php");

		}
		else
		{
			//failed to login
			echo "Sorry... Login Failure!!!";
		}

		/* free result set */
		mysqli_free_result($result);

		/* close connection */
		mysqli_close($link);
	}
	else{
		//failed to login
		echo "Login Failure. Please Enter Username and Password";
	}
}

/* Function which implements prepared statement. Not vulnerable to SQL Injection */
function SignInPreparedStmt()
{
session_start();   //starting the session for user profile page
	if(!empty($_POST['username']))   //checking the 'user' name which is from Sign-In.html, is it empty or have some text
	{
		//create link to db
		$link = mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());
		
		//escape username
		$username = mysqli_real_escape_string($link,$_POST['username']) ;
		//escape password
		$password = md5(mysqli_real_escape_string($link,$_POST['password']));
		
		//create a prepared statement and store in $stmt
		if($stmt = mysqli_prepare($link,"SELECT username,password FROM member where username = ? AND password= ?")){
			
				/* bind parameters for markers. 's' for string type, 'i' for integer type */
    			mysqli_stmt_bind_param($stmt, "ss", $username,$password);

    			/* execute query */
    			mysqli_stmt_execute($stmt);

    			/* bind result variables */
    			mysqli_stmt_bind_result($stmt, $uname,$pwd);

    			/* fetch value */
    			mysqli_stmt_fetch($stmt);			
			
			//check if there is a matching user
			if(!empty($uname) AND !empty($pwd))
			{
				//store username in session
				$_SESSION['username']=$uname;
				echo "User Successfully Logged In!!!";
				//relocate user to custom page
				header("Location: welcome.php");

			}
			else
			{
				// failed login case
				echo "Sorry... Login Failure!!!";
			}
			
			/* close statement */	
			mysqli_stmt_close($stmt);
		}
		/* close connection */
		mysqli_close($link);
	}
	else{
		// no username or password case
		echo "Login Failure. Please Enter Username and Password";
	}
}

function SignInWithStoredProcedure()
{
session_start();   //starting the session for user profile page
	if(!empty($_POST['username']))   //checking the 'user' name which is from Sign-In.html, is it empty or have some text
	{
				
		try {
			// create a new PDO connection
			$conn = new PDO("mysql:host=".DB_HOST.";dbname=".DB_NAME, DB_USER, DB_PASSWORD);
			// execute the stored procedure
			$sql = 'CALL Authenticate(:uname,:pwd,@res)';
			//prepare the sql statement and store in $stmt
			$stmt = $conn->prepare($sql);
			
			//bind username to :uname as string type
			$stmt->bindParam(':uname', $_POST['username'], PDO::PARAM_STR);
			//bind username to :pwd as string type
			$stmt->bindParam(':pwd', md5($_POST['password']), PDO::PARAM_STR);
			//execute query
			$stmt->execute();
			//close cursor
			$stmt->closeCursor();
			// execute the second query to get customer's level
			$r = $conn->query("SELECT @res AS result")->fetch(PDO::FETCH_ASSOC);
			
			//check if resulting value is 1
			if ($r['result'] == 1) {
				//store username in session
				$_SESSION['username'] = $_POST['username'];
				echo "User Successfully Logged In!!!";
				// redirect to custom welcome page
				header("Location: welcome.php");
			}
			else
			{
				//failure to login
				echo "Sorry... Login Failure!!!";
			}
		} catch (PDOException $pe) {
		    die("Error occurred:" . $pe->getMessage());
		}
	}
	else{
		//password or username not supplied
		echo "Login Failure. Please Enter Username and Password";
	}
}

// check if submit button has value set
if(isset($_POST['submit']))
{
	//Normal Login
	SignIn();
	
	//Login with Escaped String
	//SignInWithEscapeChars();
	
	//Login with Prepared Statement
	//SignInPreparedStmt();
	
	//Login with Stored Procedure
	//SignInWithStoredProcedure();
}

?>
