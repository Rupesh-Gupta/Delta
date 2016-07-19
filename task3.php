<html>
<head>
<meta charset="utf-8">
<link href="form.css" rel="stylesheet" type="text/css">
<script type="text/javascript" src="form_valid.js"></script>
<title> Sign Up Details</title>
</head>
<body>
<header>
<h1> Welcome! </h1>
</header>
<div id="wrapper">
	<section>
	<h3>Fill up the entries</h3>
	<br/>
	<div class="note">All fields are required</div><br/>

	<form method ="post" action = "<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
		Username: <input type="text" name="username" id="user_name" required></input><br/><br/>
		Password: <input type="password" pattern="[a-zA-Z0-9]+" id="password1" name="password1" 
		title="The password should be between 6-13 characters. It should include alphabets[a-z, A-Z] and numbers[0-9] only."
		required></input><br/><br/>
		Confirm Password: <input type="password" id="password2" pattern="[a-zA-Z0-9]+" name="password2"
		title="The password should be between 6-13 characters. It should include alphabets[a-z, A-Z] and numbers[0-9] only."
		required></input><br/><br/>
		Upload Profile Picture<br/>
		Choose an image
		<input type="hidden" name="MAX_FILE_SIZE" value="2000000000000">
		<input type="file" name="file" size="50" id="image" title="The image should be more than 150kB but less than 1MB."
		required/><br/><br/>
		Email Id: <input type="email" name="email" id="email" required></input><br/><br/>
		Phone No: <input type="tel" name="phone" id="phone" required></input><br/><br/>
		<input type="submit" value="submit">
	</form>
	<br/>
	</section>
	<aside>
	<h2>Sign In</h2>
	<br/><div class="note">Fields marked * are required.</div>
	<form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post">
		Username  <input type="text" name="user_name"><br/>
		<p>Or</p>
		Phone No. <input type="tel" name="ph_no"><br/><br/>
		Password<abbr>*</abbr>  <input type="password" name="pass_word" required><br/><br/>
		<input type='submit' name="login" value='login'>
	</form>
	</aside>
</div>

<?php
if(isset($_POST["login"]))
{
	@ $db = mysqli_connect("localhost","root", "", "signup" );
	if(mysqli_connect_errno())
	{
		echo "error, database connection not established";
		exit;
	}
	$user_name = mysqli_real_escape_string($db, $_POST["user_name"]);
	$pass_word = mysqli_real_escape_string($db, $_POST["pass_word"]);
	$ph_no = mysqli_real_escape_string($db, $_POST["ph_no"]);
	
	if((!$user_name && !$ph_no) || !$pass_word)
	{
		echo "Wrong Credentials! Please Try Again.";
		exit;
	}
	if($user_name!=""){
	$sql = "select * from details where user_name = '$user_name'";
	$result = mysqli_query($db, $sql);
	}
	else if($ph_no!=""){
	$sql = "select * from details where phone_no = '$ph_no'";
	$result = mysqli_query($db, $sql);
	}
	if(mysqli_num_rows($result)<1)
	{
		echo "Your login details do not match, please check and try again";
	}
	else if(mysqli_num_rows($result)==1)
	{
		$row = mysqli_fetch_assoc($result);
		if(password_verify($pass_word, $row['password']))
		{
		echo'<div id="show_details"><div id="part1"><h1>Registration Details</h1><br/><br/>Username:     '. $row["user_name"].
		'<br/><br/>Password:       '. $pass_word.
		'<br/><br/>Email Id:        '. $row["email_id"].
		'<br/><br/>Phone number:      '. $row["phone_no"].
		'</div><div id="part2">Profile Picture          <br/>'.
		'<img align="center" height="240" width="320" src="data:image/jpeg;base64,'.base64_encode( $row['content']).'"/></div></div>';
		}
	}
	mysqli_close($db);
}
?>
<?php
if(!(isset($_POST["login"]))){
if($_SERVER["REQUEST_METHOD"] == "POST")
{
	@ $db = mysqli_connect("localhost","root", "", "signup" );
	if(mysqli_connect_errno())
	{
		echo "error, database connection not established";
		exit;
	}
	
	$username = $_POST["username"];
	$password1 = $_POST["password1"];
	$password2 = $_POST["password2"];
	$email = $_POST["email"];
	$phone = $_POST["phone"];

	if((!$username || !$email || !$phone || !$password1 || !$password2))
	{
		echo "You must fill in all the required fields";
		exit;
	}
	if(strlen($password1) <6)
	{
		echo "Password must be atleast 6 characters long";
	}
	
	if($password1 != $password2){
		echo "Passwords do not match";
	}
	$pd_hash = password_hash($password1, PASSWORD_DEFAULT, array('cost'=>10));
	
	$uniq1 = "Select * from details where user_name ='$username'";
	$uniq_run1 = mysqli_query($db, $uniq1);
	$uniq2 = "Select * from details where email_id ='$email'";
	$uniq_run2 = mysqli_query($db, $uniq2);
	$uniq3 = "Select * from details where phone_no ='$phone'";
	$uniq_run3 = mysqli_query($db, $uniq3);
	if(mysqli_num_rows($uniq_run1)>0)
	{
		echo("Username: '$username' is already registered");
	}
	else if(mysqli_num_rows($uniq_run2)>0){
		echo("Email id: '$email' is already registered");
	}
	else if(mysqli_num_rows($uniq_run3)>0){
		echo("Phone no: '$phone' is already registered");
	}
	else
	{
		$filename = $_FILES['file']['name'];
		$tmpfilename = $_FILES['file']['tmp_name'];
		$filesize = $_FILES['file']['size'];
		$fileType = $_FILES['file']['type'];
		$fileext = strtolower(end(explode('.', $filename)));
		$extensions = array("jpeg", "jpg", "png");
		
		if(in_array($fileext, $extensions)===false){
			$errors[] ="extension not allowed, please choose a JPEG or PNG file.";
		}
			
		if($filesize > 8000000)
		{
			$errors[] ="Image exceeds required size constraints.";
		}
		if(empty($errors)!= true)
		{
			print_r($errors);
		}
		else{
			$fp = fopen($tmpfilename, 'r');
		$content = fread($fp, filesize($tmpfilename));
		$content = addslashes($content);
		fclose($fp);

	if(!get_magic_quotes_gpc())
	{
		$username = addslashes($username);
		$email = addslashes($email);
		$filename = addslashes($filename);
	}
	$query = "INSERT INTO details". "(user_name, password, email_id, phone_no, size, type, content, name)"
	."VALUES".
	"('$username', '$pd_hash', '$email', '$phone', '$filesize', '$fileType', '$content', '$filename')";
	$result = mysqli_query($db, $query);

	if($result)
	{
		echo "You have been successfully registered"."<br/>Your username is  ";
		echo $username;
	}
	else
	{
		echo "Oops Sorry,Trouble in registering, please retry!";
	}
	}}
	mysqli_close($db);
}
}?>
</body>
</html>
