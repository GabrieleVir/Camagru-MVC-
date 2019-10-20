<?php
require_once("models/Model.class.php");
require_once("models/User.class.php");

class UsersController extends Controller {

    /*
    This function takes an array and fill the session 
    */
    private static function fill_session_error(array $arr, $url) {
        $_SESSION['last_email'] = (array_key_exists("email", $arr)) ? $arr["email"] : "";
        $_SESSION['last_login'] = (array_key_exists("login", $arr)) ? $arr["login"] : "";
        $_GET['url'] = $url;
        Route::redirect($url, "UsersController");
    }

    public static function template_sign_up() {
        parent::createView("create_user_form");
    }

    public static function template_login() {
        parent::createView("login");
    }

    /*
    This function takes an array and verify if the keys exists inside.
    Check if the $_POST has the keys in it.
    */
    private static function info_creation_exists(array $keys, array $arr) {
        if (!array_diff($keys, array_keys($arr))) {
            return (FALSE);
        }
        return (TRUE);
    }


    private static function fill_current_user_login($valid_field, $url) {
        $user = new User;
        $content = $user->get_info($valid_field);
        if ($content != FALSE) {
            $_SESSION["current_user"] = $content[0]["login"];
            $_SESSION["current_user_email"] = $content[0]["email"];
            $_SESSION["current_user_pic"] = $content[0]["profile_pic"];
            $_SESSION["current_user_user_id"] = $content[0]["user_id"];
            // Change to createTemplate
            header("Location: index");
            return (1);
        }
        return (0);
    }

    private static function creation_user_response($return_val, $arr) {
        switch ($return_val) {
            case 1:
                if (self::send_verification_email($arr)) {
                    return "Your account has been made, <br /> please verify it by clicking the activation link that has been send to your email.<br />";
                } else {
                    $user = new User;
                    $user->delete_user($arr['login'], $arr['password']);
                    return "The verification email wasn't sent\n";
                }
            case EMAIL_EXISTS:
                self::fill_session_error($arr, "sign_up");
                return "The email already exists\n";
            case LOGIN_EXISTS:
                self::fill_session_error($arr, "sign_up");
                return "The login already exists\n";
        }
    }

    private static function activation_user_response($return_val, $email) {
        switch ($return_val) {
            case 1:
                self::fill_current_user_login($email, "index");
                return "The account has been activated. Welcome to the BDClub";
            case USER_DONT_EXIST:
                $_GET["url"] = "index";
                // Change to createTemplate
                require_once("views/header.module.php");
                require_once("views/index.view.php");
                require_once("views/footer.module.php");
                return "The email already exists\n";
        }
    }

    /*
    create_user(array $kwargs)
    */
    public static function create_user(array $kwargs) {
        try {
            $keys = ["password", "password2", "login", "email", "profile_pic"];
            if ((self::info_creation_exists($keys, $kwargs)) == FALSE) {
                self::fill_session_error($kwargs, "sign_up");
                return "Error: empty inputs when creating an user\n";
            }
            if ($kwargs["password"] == $kwargs["password_verif"]) {
                $kwargs_model = [
                    "email" => array_key_exists("email", $kwargs) ? $kwargs["email"] : "",
                    "login" => array_key_exists("login", $kwargs) ? $kwargs["login"] : "",
                    "password" => array_key_exists("password", $kwargs) ? hash("whirlpool", $kwargs["password"]) : "",
                    "profile_pic" => array_key_exists("profile_pic", $kwargs) ? $kwargs["profile_pic"] : "assets",
                    "verif_hash" => md5(rand(9101994, 11021994))
                ];
                $user = new User;
                if (self::email_valid($kwargs_model['email']) && self::login_valid($kwargs_model['login'])) {
                    return self::creation_user_response($user->create_user($kwargs_model), $kwargs_model);
                } else {
                    self::fill_session_error($kwargs, "sign_up "Email or login are not well formatted\n";
                }
            } else {
                self::fill_session_error($kwargs, "sign_up");
                return "Password verification and password are not the same";
            }
        }
        catch (Exception $e) {
            echo "FATAL ERROR ! " . $e->getMessage();
        }
    }

    /*
    send_verification_email($email, $hash) verify if the hash and the email sent by the user correspond to a verification email.
    Then, it'll send the user logged on the index.
    */
    private static function send_verification_email($user_info) {
        if (self::email_valid($user_info["email"]) && self::md5_valid($user_info["verif_hash"])) {
            $to = "Gabriele_Virga@hotmail.com";
            $from = "gvirga@student.s19.be";
            $headers = "MIME-Version: 1.0" . "\r\n"; 
            $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
            $headers .= 'From: '.$from. "\r\n"; 
            $subject = "<h1 class='test'> Verification mail </h1>";
            $link = "http://localhost:8080/Camagru/index.php?email=" . $user_info['email'] . "&hash=" . $user_info["verif_hash"] . "";
            $message = '<a href="' . $link . '"> localhost:8080/Camgru/verif </a>';
            if (mail($to, $subject, $message, $headers)) {
                return (1);
            } else {
                return (0);
            }
        }
    }

    public static function activate_account($email, $hash) {
        if (self::email_valid($email) && self::md5_valid($hash)) {
            $user = new User;
            $return_value = $user->activate_account($email, $hash);
            return self::activation_user_response($return_value, $email);
        }
    } 
    

	/*
	email_valid($email) take an user input and verify if the input is a well-formatted email.
	*/
	private static function email_valid($email) {
        if (preg_match('/^(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){255,})(?!(?:(?:\x22?\x5C[\x00-\x7E]\x22?)|(?:\x22?[^\x5C\x22]\x22?)){65,}@)(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22))(?:\.(?:(?:[\x21\x23-\x27\x2A\x2B\x2D\x2F-\x39\x3D\x3F\x5E-\x7E]+)|(?:\x22(?:[\x01-\x08\x0B\x0C\x0E-\x1F\x21\x23-\x5B\x5D-\x7F]|(?:\x5C[\x00-\x7F]))*\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+(?:-[a-z0-9]+)*\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:(?:xn--)[a-z0-9]+))(?:-[a-z0-9]+)*)|(?:\[(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9][:\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:){5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\.(?:(?:25[0-5])|(?:2[0-4][0-9])|(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\]))$/iD',$email)) {
            return (1);
        }
        return (0);
    }
    /*
    md5_valid($md5_hash) takes an hash and verify if it has only the char authorized for md5. 
    */
    private static function md5_valid($hash) {
        if (preg_match('/^[0-9A-F]+$/iD', $hash)) {
            return (1);
        }
        return (0);
    }
    /*
    login_valid($login) take an user input and verify if the input is a well-formatted login.
	*/
	private static function login_valid($login) {
        if (preg_match("/^(?=.{5,26}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$/",$login)) {
            return (1);
        }
		return (0);
	}

	private static function login_verif(array $kwargs) {
	    $user = new User;
        if (array_key_exists("login", $kwargs) && !empty($kwargs["login"])) {
            if (self::login_valid($kwargs["login"]) || self::email_valid($kwargs["login"]) 
            && $user->auth_user($kwargs["login"], hash("whirlpool", $kwargs["password"]))) {
                return (self::fill_current_user_login($kwargs["login"], "index"));
            }
        }
        return (0);
    }

	public static function login(array $kwargs) {
        if (array_key_exists("password", $kwargs) && !empty($kwargs["password"])) {
            if (self::login_verif($kwargs)) {
                return ("Logged");
            } else {
                self::fill_session_error($kwargs, "login");
                return ("Wrong login\n");
            }
        }
        self::fill_session_error($kwargs, "login");
        return ("Empty password");
    }

    public static function logout() {
        session_destroy();
        $_GET["url"] = "index";
        // Change to createTemplate
        header("Location: index");
    }
}