<?php
// Function which is able to  look for double data in the same field
function __isuserhere($item,$nameOfField,$db){
    
    $Booll = FALSE;
    $isSame=" ";
    $sql="SELECT $nameOfField FROM user";
    $result = $db->prepare($sql);
    $result->execute();
    $items = $result->fetchAll(PDO::FETCH_COLUMN); //Maybye Fetch? 
    foreach ($items as $isSame){
        if($item == $isSame){
            $Booll = TRUE;
        }
    }
    return $Booll;
}

function __isbeerhere($item,$nameOfField,$db){
    
    $Booll = FALSE;
    $isSame=" ";
    $sql="SELECT $nameOfField FROM beer";
    $result = $db->prepare($sql);
    $result->execute();
    $items = $result->fetchAll(PDO::FETCH_COLUMN); //Maybye Fetch? 
    foreach ($items as $isSame){
        if($item == $isSame){
            $Booll = TRUE;
        }
    }
    return $Booll;
}

function test_input($data) {
    $data = trim($data); // Remove whitespace and other predifined caracter from both sides of a string
    $data = stripslashes($data);// Remove backslashes
    $data = htmlspecialchars($data);// Convert predifined caracters
    return $data;
}

 // final function to send safe data 
function __sendUserData($name,$firstname,$username,$mail,$password,$rank,$db){
        
    $sql="INSERT INTO user(name,firstname,username,mail,password,ID_rank) VALUES (?,?,?,?,?,?)";
    $result = $db->prepare($sql);
    $result->execute(array($name,$firstname,$username,$mail,$password,$rank));

}

function __findPP($mail,$password,$db){
   
    $sql = "SELECT profile_picture FROM user WHERE mail = ? AND password = ?";
    $qry = $db->prepare($sql);
    $qry->execute([$mail,$password]);
    $result = $qry->fetch();
    $profile_picture = $result['profile_picture'];

    return $profile_picture;

}