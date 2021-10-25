<?php

include_once 'db-connect.php';
include_once 'magazine.php';

class User {

    private $db;
    private $db_table = "users";
    private $db_table2 = "likes";

    public function __construct() {

        $this->db = new DbConnect();
    }

    public function isLoginExist($email) {

        $query = "select * from " . $this->db_table . " where user_email = :user_email Limit 1";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_email', $email);
        $result->execute();
        $rows = $result->rowCount();

        if ($rows > 0) {

            $this->db = null;
            return true;
        } else {

            $this->db = null;
            return false;
        }
    }

    public function isUserActive($email) {
        $query = "select registered from " . $this->db_table . " where user_email = :user_email Limit 1";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_email', $email);
        $result->execute();

        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $registered = $result->registered;
        }
        if ($registered == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function isEmailExist($email) {

        $query = "select * from " . $this->db_table . " where user_email = :user_email";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_email', $email);
        $result->execute();
        $rows = $result->rowCount();

        if ($rows > 0) {

            return true;
        }

        return false;
    }

    public function isValidEmail($email) {
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    public function createNewRegisterUser($regtoken) {

        $query = "UPDATE " . $this->db_table . " SET regtoken = NULL, registered = TRUE WHERE regtoken = :regtoken AND regexpiry > NOW()";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':regtoken', $regtoken);
        $result->execute();

        if ($result == true) {
            $json['success'] = 1;
            $json['message'] = "Successfully registered the user";
        } else {

            $json['success'] = 0;
            $json['message'] = "Error in registering. Token has expired.";
        }

        $this->db = null;
        return $json;
    }

    public function loginUsers($email, $verify) {

        $json = array();

        $canUserLogin = $this->isLoginExist($email);

        if ($canUserLogin && $verify) {

            $json['success'] = 1;
            $json['message'] = "Successfully logged in";
        } else {

            $json['success'] = 0;
            $json['message'] = "Incorrect details";
        }
        $this->db = null;
        return $json;
    }

    public function passwordVerify($email, $password) {

        $query = "select password from " . $this->db_table . " where user_email = :user_email Limit 1";

        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_email', $email);
        $result->execute();

        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        if (empty($results)) {
            return false;
        } else {

            foreach ($results as $result) {
                $hash = $result->password;
            }

            $verify = password_verify($password, $hash);

            if ($verify) {
                return true;
            }
        }
    }

    public function resetPassword($email) {

        // tee tähän sellainen että hakee käyttäjän nimen myös tuohon automaattiseen mailiin
        $query = "SELECT user_email, authtoken FROM " . $this->db_table . " WHERE user_email = :user_email";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_email', $email);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        if (empty($results)) {
            return 0;
        } else {

            $token = $this->generateToken($email);

            foreach ($results as $result) {
                $to = $result->user_email;
            }

            if ($to) {
                $link = "http://localhost/AikaOyAb/uusisalasana.php?authtoken=";
                $subject = "Reset your password on Aika";
                $msg = "Hi there you fucking user, click on this " . $link . $token . " to reset your password. Token is valid for 15 minutes.";
                $msg = wordwrap($msg, 70);
                $headers = "From: aika@aika.fi";
                mail($to, $subject, $msg, $headers);
                return 1;
            }
        }
        $this->db = null;
    }

    public function generateToken($email) {

        $token = md5(uniqid($email, true));

        $query = "UPDATE " . $this->db_table . " SET authtoken = :authtoken WHERE user_email= :user_email";
        $query2 = "UPDATE " . $this->db_table . " SET authexpiry = NOW() + INTERVAL 15 MINUTE WHERE user_email= :user_email";

        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':authtoken', $token);
        $result->bindParam(':user_email', $email);

        $result2 = $this->db->getDb()->prepare($query2);
        $result2->bindParam(':user_email', $email);

        $result->execute();
        $result2->execute();

        $this->db = null;

        return $token;
    }

    public function savePassword($authtoken, $hashed_password) {

        $query = "SELECT user_email, authtoken, authexpiry FROM " . $this->db_table . " WHERE authtoken = :authtoken";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':authtoken', $authtoken);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        if (empty($results)) {
            return 0;
        } else {

            foreach ($results as $result) {
                $to = $result->user_email;
                $token = $result->authtoken;
            }

            if ($to) {
                $query = "UPDATE " . $this->db_table . " SET password = :password WHERE user_email = :user_email AND authtoken = :authtoken AND authexpiry > NOW()";
                $result = $this->db->getDb()->prepare($query);
                $result->bindParam(':password', $hashed_password);
                $result->bindParam(':user_email', $to);
                $result->bindParam(':authtoken', $token);
                $result->execute();

                return 1;
            }
        }
    }

    public function changePassword($hashed_password, $email) {

        $query = "UPDATE " . $this->db_table . " SET password = :password WHERE user_email = :user_email";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':password', $hashed_password);
        $result->bindParam(':user_email', $email);
        $result->execute();

        return 1;
    }

    public function changeFirstname($email, $firstname) {

        $query = "UPDATE " . $this->db_table . " SET firstname = :firstname WHERE user_email = :user_email";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':firstname', $firstname);
        $result->bindParam(':user_email', $email);
        $result->execute();

        return 1;
    }

    public function changeLastname($email, $lastname) {

        $query = "UPDATE " . $this->db_table . " SET lastname = :lastname WHERE user_email = :user_email";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':lastname', $lastname);
        $result->bindParam(':user_email', $email);
        $result->execute();

        return 1;
    }

    public function changeEmail($newEmail, $loginid) {

        $query = "UPDATE " . $this->db_table . " SET user_email = :user_email WHERE loginid = :loginid";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_email', $newEmail);
        $result->bindParam(':loginid', $loginid);
        $result->execute();

        return 1;
    }

    public function removeToken($authtoken) {

        $query = "UPDATE " . $this->db_table . " SET authtoken = NULL WHERE authtoken = :authtoken";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':authtoken', $authtoken);
        $result->execute();
        $this->db = null;
    }

    public function validateToken($authtoken) {
        $query = "SELECT authtoken from " . $this->db_table . " WHERE authtoken = :authtoken AND authexpiry > NOW()";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':authtoken', $authtoken);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        if (empty($results)) {
            return 0;
        } else {
            return 1;
        }
    }

    public function validateRegToken($regtoken) {
        $query = "SELECT regtoken from " . $this->db_table . " WHERE regtoken = :regtoken AND regexpiry > NOW()";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':regtoken', $regtoken);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        if (empty($results)) {
            return 0;
        } else {
            return 1;
        }
    }

    public function generateRegToken($email) {

        $token = md5(uniqid($email, true));

        return $token;
    }

    public function removeRegToken($regtoken) {

        $query = "UPDATE " . $this->db_table . " SET regtoken = NULL WHERE regtoken = :regtoken";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':regtoken', $regtoken);
        $result->execute();
        $this->db = null;
    }

    public function confirmRegistration($email, $firstname, $lastname, $password) {

        $isEmailExisting = $this->isEmailExist($email);

        if ($isEmailExisting) {

            $json['success'] = 0;
            $json['message'] = "Probably the email already exists";
        } else {

            $isValid = $this->isValidEmail($email);

            if ($isValid) {

                $token = $this->generateRegToken($email);
                $loginid = $this->generateRegToken($email);

                $query = "insert into " . $this->db_table . " (firstname, lastname, password, user_email, created_at, updated_at, regtoken, regexpiry, loginid) values (:firstname, :lastname, :password, :user_email, NOW(), NOW(), :regtoken, NOW() + INTERVAL 15 MINUTE, :loginid)";
                $result = $this->db->getDb()->prepare($query);
                $hashed_password = password_hash($password, PASSWORD_ARGON2ID, ['memory_cost' => 2048, 'time_cost' => 4, 'threads' => 3]);
                $result->bindParam(':firstname', $firstname);
                $result->bindParam(':lastname', $lastname);
                $result->bindParam(':user_email', $email);
                $result->bindParam(':password', $hashed_password);
                $result->bindParam(':regtoken', $token);
                $result->bindParam(':loginid', $loginid);
                $result->execute();

                if ($result == true) {

//                    $link = "http://localhost/AikaOyAb/confirmation.php?regtoken=";
//                    $subject = "Confirm your account on Aika";
//                    $msg = "Hi there " . $firstname . ", click on this " . $link . $token . " to confirm your account. Token is valid for 15 minutes.";
//                    $msg = wordwrap($msg, 70);
//                    $headers = "From: aika@aika.fi";
//                    mail($email, $subject, $msg, $headers);
                    $json['success'] = 1;
                    $json['message'] = "Successfully sent confirmation e-mail. Check your mailbox.";
                } else {

                    $json['success'] = 0;
                    $json['message'] = "The username/email already exists";
                }

                $this->db = null;
            } else {

                $json['success'] = 0;
                $json['message'] = "Email Address is not valid";
            }
        }

        return $json;
    }

    public function subscribe($magazine, $loginid) {

        $query = "SELECT $magazine FROM " . $this->db_table . " WHERE loginid = :loginid";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':loginid', $loginid);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $status = $result->$magazine;
        }

        if ($status == 0) {
            $query = "UPDATE " . $this->db_table . " SET $magazine = TRUE WHERE loginid = :loginid";
            $result = $this->db->getDb()->prepare($query);
            $result->bindParam(':loginid', $loginid);
            if ($result->execute() == true) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function unSubscribe($magazine, $email) {

        $query = "SELECT $magazine FROM " . $this->db_table . " WHERE user_email = :user_email";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_email', $email);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $status = $result->$magazine;
        }

        if ($status == 1) {
            $query = "UPDATE " . $this->db_table . " SET $magazine = FALSE WHERE user_email = :user_email";
            $result = $this->db->getDb()->prepare($query);
            $result->bindParam(':user_email', $email);
            if ($result->execute() == true) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    //returns autoincrement user_id
    public function getID($loginid) {
        $query = "SELECT user_id FROM " . $this->db_table . " WHERE loginid = :loginid";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':loginid', $loginid);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $id = $result->user_id;
        }

        return $id;
    }

    public function getEmail($loginid) {
        $query = "SELECT user_email FROM " . $this->db_table . " WHERE loginid = :loginid";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':loginid', $loginid);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $email = $result->user_email;
        }

        return $email;
    }

    public function getLoginId($email) {
        $query = "SELECT loginid FROM " . $this->db_table . " WHERE user_email = :user_email";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_email', $email);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $loginid = $result->loginid;
        }

        return $loginid;
    }

    public function getFirstname($email) {
        $query = "SELECT firstname FROM " . $this->db_table . " WHERE user_email = :user_email";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_email', $email);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $firstname = $result->firstname;
        }
        if (empty($firstname)) {
            return "empty";
        } else {
            return $firstname;
        }
    }

    public function getLastname($email) {
        $query = "SELECT lastname FROM " . $this->db_table . " WHERE user_email = :user_email";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_email', $email);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $lastname = $result->lastname;
        }
        if (empty($lastname)) {
            return "empty";
        } else {
            return $lastname;
        }
    }

    public function updateProfilePhoto($email, $img) {
        $query = "UPDATE " . $this->db_table . " SET profile_image = :profile_image WHERE user_email= :user_email";

        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':profile_image', $img);
        $result->bindParam(':user_email', $email);

        $result->execute();

        if ($result) {
            $json['success'] = 1;
            $json['message'] = "Successfully changed profile picture.";
        } else {
            $json['success'] = 0;
            $json['message'] = "Something went wrong.";
        }

        $this->db = null;
        return $json;
    }

    public function getProfilePhoto($email) {
        $query = "SELECT profile_image FROM " . $this->db_table . " WHERE user_email = :user_email";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_email', $email);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $img = $result->profile_image;
        }

        return $img;
    }

    public function getUserId($email) {
        $query = "SELECT user_id FROM " . $this->db_table . " WHERE user_email = :user_email";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_email', $email);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $id = $result->user_id;
        }

        return $id;
    }

    public function getLikes($email) {
        $userobject = new User();
        $magazineobject = new Magazine();
        $json['likes'] = array();
        $id = $userobject->getUserId($email);
        $query = "SELECT magazine_id FROM " . $this->db_table2 . " WHERE user_id = :user_id AND magazine_id IS NOT NULL";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_id', $id);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();
        $rows = $result->rowCount();

        for ($i = 0; $i < $rows; $i++) {
            array_push($json, $magazineobject->getMagName($results[$i]->magazine_id));
        }
        return $json;
    }

    public function getFirstLastname($id) {
        $query = "SELECT firstname, lastname FROM " . $this->db_table . " WHERE user_id = :user_id";
        $result = $this->db->getDb()->prepare($query);
        $result->bindParam(':user_id', $id);
        $result->execute();
        $result->setFetchMode(PDO::FETCH_OBJ);
        $results = $result->fetchAll();

        foreach ($results as $result) {
            $name = $result->firstname . " " . $result->lastname;
        }
        
        return $name;
    }

}

?>