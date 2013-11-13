<?php

        // dbConfig.php is a file that contains your
        // database connection information. This
        // tutorial assumes a connection is made from
        // this existing file.
        include ("dbConfig.php");
        
//Input vaildation and the dbase code
        if ( $_GET["op"] == "reg" )
  {
  $bInputFlag = false;
  foreach ( $_POST as $field )
        {
        if ($field == "")
    {
    $bInputFlag = false;
    }
        else
    {
    $bInputFlag = true;
    }
        }
  // If we had problems with the input, exit with error
  if ($bInputFlag == false)
        {
        die( "Problem with your registration info. "
    ."Please go back and try again.");
        }

  // Fields are clear, add user to database
  //  Setup query	
  $username=$_POST['username'];
  $password=$_POST['password'];
  $name=$_POST['name'];
  $lastname=$_POST['lastname'];
  $sede=$_POST['sede'];
  $password=md5($password);
  
  // Fields are clear, add user to database
  //  Setup query
  $q = "INSERT INTO `ga000848_StockDB`.`user` (`username`, `name`, `lastname`, `sede`, `password`) VALUES ('$username', '$name', '$lastname', '$sede', '$password');";

  $r = mysql_query($q);
  
  // Make sure query inserted user successfully
  if ( !mysql_insert_id() )
        {
        die("Error: User not added to database.");
        }
  else
        {
        // Redirect to thank you page.
        Header("Location: register.php?op=thanks");
        }
  } // end if


//The thank you page
        elseif ( $_GET["op"] == "thanks" )
  {
  echo "<h2>Thanks for registering!</h2>";
  }
  
//The web form for input ability
        else
  {
  echo "<form action=\"?op=reg\" method=\"POST\">\n";
  echo "Username: <input name=\"username\" MAXLENGTH=\"16\"><br />\n";
  echo "Password: <input type=\"password\" name=\"password\" MAXLENGTH=\"16\"><br />\n";
  echo "Nombre: <input name=\"name\" MAXLENGTH=\"25\"><br />\n";
  echo "Apellido: <input name=\"lastname\" MAXLENGTH=\"25\"><br />\n";
  echo "Sede: <input name=\"sede\" MAXLENGTH=\"25\"><br />\n";
  echo "<input type=\"submit\">\n";
  echo "</form>\n";
  }
        // EOF
        ?>