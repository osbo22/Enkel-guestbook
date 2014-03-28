<?php

// värden för pdo
$host	  = "localhost";
$dbname	  = "guestbook";
$username = "guestbook";
$password = "12345";

// göra pdo
$dsn = "mysql:host=$host;dbname=$dbname";
$attr = array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC);

$pdo = new PDO($dsn, $username, $password, $attr);
if ($pdo) 
{
	// visa alla användare (ul)
	echo "<ul>";
	echo "<li><a href=\"index.php\"> All users </a></li>";
	foreach ($pdo->query("SELECT * FROM users ORDER BY name") as $row) 
	{
		echo "<li><a href=\"?user_id={$row['id']}\">{$row['name']}</a></li>";
	}
	echo "</ul>";
	echo "<hr />";
	
	if(!empty($_GET))
	{
		// om user klickat på ett namn, visa dess inlägg
		$_GET = null;
		$user_id = filter_input(INPUT_GET, 'user_id', FILTER_VALIDATE_INT);
		$statement = $pdo->prepare("SELECT posts.*,users.name FROM posts JOIN users ON users.id=posts.user_id WHERE user_id=:user_id ORDER BY date");
		$statement->bindParam(":user_id", $user_id);
	
		if($statement->execute())
		{
			echo "<h3>This users post(s).</h3>";
			while($row = $statement->fetch())
			{
				echo "<p>{$row['date']} by {$row['name']} <br /> {$row['post']}</p>";
			}
		}
	}
	else
	{
		// alla posts som alla användare skrivit
		echo "<h3>All posts.</h3>";
		foreach ($pdo->query("SELECT posts.*,users.name AS user_name FROM posts JOIN users on users.id=posts.user_id ORDER BY date") as $row) 
		{
			echo "<p>{$row['date']} by {$row['user_name']} <br /> {$row['post']}</p>";
		}
	}

}
else 
{
	echo "Not connected";
}
?>
