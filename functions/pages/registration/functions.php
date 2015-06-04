<?php
function globals_set() {
    global $DBCon;

    //REGISTER
    if (isset($_POST['username']) || isset($_POST['password']) || isset($_POST['password_conf']) || isset($_POST['email'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
        $pass_conf = $_POST['password_conf'];
        $email = $_POST['email'];
        $name = $_POST['name'];
        $exp = 0;
        $maxhp = 100;
        $hp = 100;
        $maxstamina = 100;
        $stamina = 100;
        $ip = getUserIP();

        $user_check = $DBCon->query("SELECT Username FROM mod_characters WHERE Username='$username'");
        $do_user_check =  $DBCon->fetch_assoc($user_check);
        //Now if email is already in use
        $email_check = $DBCon->query("SELECT Email FROM mod_characters WHERE Email='$email'");
        $do_email_check = $DBCon->fetch_assoc($email_check);
        //Now display errors
        if($do_user_check > 0){
            die("Username is already in use!<br>");
        }
        if($do_email_check > 0){
            die("Email is already in use!");
        }
        //Now let's check does passwords match
        if($password != $pass_conf){
            die("Passwords don't match!");
        }

        //If everything is okay let's register this user
        $psw = md5($password);
        $DBCon->query("INSERT INTO mod_characters
(Username, Name, Email, Password, Experiance, MaxHp, Hp, MaxStamina, Stamina, Ip)
VALUES
('$username', '$name' ,'$email' ,'$psw','$exp','$maxhp', '$hp','$maxstamina', '$stamina', '$ip' )");
        echo 'REGISTERED';
    }

    //LOGIN
    if (isset($_POST['admin_form'])) {

        $_POST = str_replace("\'", "", $_POST);
        $login = strip_tags($_POST['username']);
        $pass = md5(strip_tags($_POST['password']));
        $query = "SELECT count(*) as cnt FROM mod_characters WHERE Banned=0 AND Username='$login' AND Password='$pass'";
        $result = $DBCon->query($query);
        $row = $DBCon->fetch_assoc($result);
        if ($row['cnt'] != 1) {
            $errors = 'Neteisingi prisijungimo duomenys';
        }
        if ($errors == NULL) {
            $_SESSION['Drd_Username'] = $login;
            /*$Stay = $_POST['remember'];
            if ($Stay == 'on') {
                setcookie('drdpassword', $pass, time() + 60 * 60 * 24 * 100);
                setcookie('drdusername', $login, time() + 60 * 60 * 24 * 100);
            }
            header('location:/admin/main');
            */
        }
    }


    /*
    $baseLink = '/' . $DBCon->url_arr[1];
    //Load specific news item
    if (isset($DBCon->url_arr[2]) && !is_numeric($DBCon->url_arr[2])) {
        $query = "SELECT Title_$DBCon->lang AS Title, Description_$DBCon->lang AS Content, Alias_$DBCon->lang AS Alias,
Date_Created FROM mod_news WHERE Alias_$DBCon->lang = '$DBCon->Alias'ORDER BY Date_Created";
        $row = $DBCon->query($query);
        $row = $DBCon->fetch_assoc($row);

        $DBCon->Assign('item', $row);
        $DBCon->Assign('template', dirname(__FILE__) . '/newsItem.tpl');
    } else { // Load news list
        $query = "SELECT Title_$DBCon->lang AS Title, Short_Desc_$DBCon->lang AS Short, Alias_$DBCon->lang AS Alias,
Date_Created FROM mod_news ORDER BY Date_Created " . getLimit(5);
        $result = $DBCon->query($query);
        $news = null;
        while ($row = $DBCon->fetch_assoc($result)) {
            $news[] = $row;
        }
        $DBCon->Assign('pages', do_pagination(getTableRowCount('mod_news'), 5, $baseLink));
        $DBCon->Assign('news', $news);


    }
    $DBCon->Assign('baseLink', $baseLink);
        */
$DBCon->Assign('template', dirname(__FILE__) . '/registration.tpl');
}

function getUserIP()
{
    $client  = @$_SERVER['HTTP_CLIENT_IP'];
    $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
    $remote  = $_SERVER['REMOTE_ADDR'];

    if(filter_var($client, FILTER_VALIDATE_IP))
    {
        $ip = $client;
    }
    elseif(filter_var($forward, FILTER_VALIDATE_IP))
    {
        $ip = $forward;
    }
    else
    {
        $ip = $remote;
    }

    return $ip;
}