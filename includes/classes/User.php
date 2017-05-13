<?php 
/**
 * User class file
 *
 * PHP version 5.5.38
 *
 * @category  User
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   Akia's Public License
 * @link      localhost:8080
 */

require_once 'DbItem.php';

/**
 * User class that holds all its database operations.
 *
 * @category  Class
 * @package   User
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   Akia's Public License
 * @link      localhost:8080
 */
class User extends DbItem
{
    private $_first;
    private $_last;
    private $_email;
    private $_password;
    private static $_fields = ["id", "first", "last", "email", "password"];
    
    /**
     * Constructs a user object given some values.
     *
     * @param DBConnectionObject $db     Database object we'll be using
                                         to access user data.
     * @param Array              $fields Fields we're setting for the object.
     */
    function __construct($db, $fields=null)
    {
        parent::__construct($db, 'user');
        $this->_first = "";
        $this->_last = "";
        $this->_email = "";
        $this->_password = "";
        if (isset($fields)) {
            if ($this->validFields($fields, User::$_fields)) {
                $this->setFields($fields);
            } else {
                throw new Exception("Invalid fields.", 1);
            }
        }
    }

    /**
     * Gets the first name.
     *
     * @return String the first name.
     */
    public function getFirstName()
    {
        return $this->_first;
    }

    /**
     * Sets the first name.
     *
     * @param String $value First name.
     *
     * @return Boolean whether set was successful or not.
     */
    public function setFirstName($value)
    {
        if (isset($value) && !empty($value)) {
            $this->_first = $value;
            return true;
        }
        return false;
    }

    /**
     * Gets the first name.
     *
     * @return String the first name.
     */
    public function getLastName()
    {
        return $this->_last;
    }

    /**
     * Sets the last name.
     *
     * @param String $value Last name.
     *
     * @return Boolean whether set was successful or not.
     */
    public function setLastName($value)
    {
        if (isset($value) && !empty($value)) {
            $this->_last = $value;
            return true;
        }
        return false;
    }

    /**
     * Gets the email.
     *
     * @return String the email.
     */
    public function getEmail()
    {
        return $this->_email;
    }

    /**
     * Sets the email.
     *
     * @param String $value email.
     *
     * @return Boolean whether set was successful or not.
     */
    public function setEmail($value)
    {
        if (isset($value) && !empty($value) && $this->validEmail($value)) {
            $this->_email = $value;
            return true;
        }
        return false;
    }

    /**
     * Gets the password.
     *
     * @return String the password.
     */
    public function getPassword()
    {
        return $this->_password;
    }

    /**
     * Sets the password.
     *
     * @param String $value password.
     *
     * @return Boolean whether set was successful or not.
     */
    public function setPassword($value)
    {
        if (isset($value) && !empty($value)) {
            $this->_password = $value;
            return true;
        }
        return false;
    }
    
    /**
     * Returns whether the email is valid.
     * Resource:
     *      http://stackoverflow.com/questions/12026842/how-to-validate-an-email-address-in-php
     *
     * @param String $email Email we're validating.
     *
     * @return Boolean whether email is valid or not.
     */
    public function validEmail($email)
    {
        $pattern = '/^(?!(?:(?:\\x22?\\x5C[\\x00-\\x7E]\\x22?)|'.
        '(?:\\x22?[^\\x5C\\x22]\\x22?)){255,})(?!(?:(?:\\x22?\\x5C'.
        '[\\x00-\\x7E]\\x22?)|(?:\\x22?[^\\x5C\\x22]\\x22?)){65,}@)'.
        '(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D\\x3F'.
        '\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-\\x1F'.
        '\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*\\x22))'.
        '(?:\\.(?:(?:[\\x21\\x23-\\x27\\x2A\\x2B\\x2D\\x2F-\\x39\\x3D'.
        '\\x3F\\x5E-\\x7E]+)|(?:\\x22(?:[\\x01-\\x08\\x0B\\x0C\\x0E-'.
        '\\x1F\\x21\\x23-\\x5B\\x5D-\\x7F]|(?:\\x5C[\\x00-\\x7F]))*'.
        '\\x22)))*@(?:(?:(?!.*[^.]{64,})(?:(?:(?:xn--)?[a-z0-9]+'.
        '(?:-+[a-z0-9]+)*\\.){1,126}){1,}(?:(?:[a-z][a-z0-9]*)|(?:'.
        '(?:xn--)[a-z0-9]+))(?:-+[a-z0-9]+)*)|(?:\\[(?:(?:IPv6:(?:'.
        '(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){7})|(?:(?!(?:.*[a-f0-9]'.
        '[:\\]]){7,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?::(?:'.
        '[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,5})?)))|(?:(?:IPv6:(?:(?:'.
        '[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){5}:)|(?:(?!(?:.*[a-f0-9]:)'.
        '{5,})(?:[a-f0-9]{1,4}(?::[a-f0-9]{1,4}){0,3})?::(?:[a-f0-9]{1,4}'.
        '(?::[a-f0-9]{1,4}){0,3}:)?)))?(?:(?:25[0-5])|(?:2[0-4][0-9])|'.
        '(?:1[0-9]{2})|(?:[1-9]?[0-9]))(?:\\.(?:(?:25[0-5])|(?:2[0-4][0-9])|'.
        '(?:1[0-9]{2})|(?:[1-9]?[0-9]))){3}))\\]))$/iD';

        return (preg_match($pattern, $email) === 1);
    }

    /**
     * Updates the object data in the database.
     *
     * @return Int number of users saved. Will be 1 or 0.
     */
    public function save()
    {
        $qry = "update `user`
            set
                first=:first,
                last=:last,
                email=:email,
                password=:password
            where id=:id";
        $stmt = $this->db->prepare($qry);
        $stmt->execute(
            array(
                ":first" => $this->_first,
                ":last" => $this->_last,
                ":email" => $this->_email,
                ":password" => $this->_password,
                ":id" => $this->id
            )
        );
        return $stmt->rowCount();
    }

    /**
     * Loads the user data to this object given the ID.
     *
     * @param Int $id ID of the user we're trying to get.
     *
     * @return Boolean on whether it was successful or not.
     */
    public function loadById($id)
    {
        $result = $this->getById($id);
        if ($result) {
            $this->id = $result->id;
            $this->_first = $result->first;
            $this->_last = $result->last;
            $this->_email = $result->email;
            $this->_password = $result->password;
            unset($result);
            return true;
        }
        return false;
    }

    /**
     * Loads the user data to this object given the email.
     *
     * @param String $email Email of the user we're trying to get.
     *
     * @return Boolean on whether it was successful or not.
     */
    public function loadByEmail($email)
    {
        $result = $this->getUserByEmail($email);
        if ($result) {
            $this->_first = $result->first;
            $this->_last = $result->last;
            $this->_email = $result->email;
            $this->_password = $result->password;
            unset($result);
            return true;
        }
        return false;
    }

    /**
     * Sets the fields of the object if given.
     *
     * @param Array $fields Fields we're going to set the object with.
     *
     * @return Boolean on whether setting was successful or not.
     */
    public function setFields($fields)
    {
        $res = false;

        if (isset($fields) && is_array($fields)) {
            if (array_key_exists('id', $fields)) {
                if (!$this->setId($fields['id'])) {
                    throw new Exception("id must be greater than 0.", 1);
                }
            }
            if (array_key_exists('first', $fields)) {
                if (!$this->setFirstName($fields['first'])) {
                    throw new Exception("first name can't be empty.", 1);
                }
            }
            if (array_key_exists('last', $fields)) {
                if (!$this->setLastName($fields['last'])) {
                    throw new Exception("last name can't be empty.", 1);
                }
            }
            if (array_key_exists('email', $fields)) {
                if (!$this->setEmail($fields['email'])) {
                    throw new Exception("email is invalid.", 1);
                }
            }
            if (array_key_exists('password', $fields)) {
                if (!$this->setPassword($fields['password'])) {
                    throw new Exception("password can't be empty.", 1);
                }
            }
            return true;
        }
        return false;
    }

    /**
     * Checks fields values if they are valid. Returns 0 the moment it finds
     * an invalid field value.
     *
     * @param Array $fields Field values to be validated.
     *
     * @return Boolean whether all fields have valid values or not.
     */
    public function validFieldValues($fields)
    {
        foreach ($fields as $field => $val) {
            if (isset($fields) && is_array($fields)) {
                if (array_key_exists('id', $fields)) {
                    if (!(isset($fields['id']) && !empty($fields['id']))) {
                        return false;
                    }
                } else if (array_key_exists('first', $fields)) {
                    if (!(isset($fields['first']) && !empty($fields['first']))) {
                        return false;
                    }
                } else if (array_key_exists('last', $fields)) {
                    if (!(isset($fields['last']) && !empty($fields['last']))) {
                        return false;
                    }
                } else if (array_key_exists('email', $fields)) {
                    if (!(isset($fields['email']) && !empty($fields['email']))) {
                        return false;
                    }
                } else if (array_key_exists('password', $fields)) {
                    if (!isset($fields['password']) || empty($fields['password'])) {
                        return false;
                    }
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Gets a db user object given the email. This is not the same instance of
     * this User class. The object however will have all its fields accessible
     * to the programmer.
     *
     * @param String $email email of the user we're trying to get.
     *
     * @return Null or an object of the user.
     */
    public static function getUserByEmail($email)
    {
        $stmt = $this->db->prepare("select * from `user` where email=" . $email);
        $stmt->execute();
        return $stmt->fetchObject();
    }

    /**
     * Adds a user to the database given a list of fields. Must have all 4 values.
     *
     * @param Array $fields Values of the users we're adding.
     *
     * @return Int number of users added. Will be 1 or 0.
     */
    public function add($fields)
    {
        if ($this->validFields($fields, User::$_fields) == count(User::$_fields) - 1
            && $this->setFields($fields)
        ) {
            $stmt = $this->db->prepare(
                "insert into `{$this->table}` (first, last, email, password)
                values (:first, :last, :email, :password)"
            );
            $stmt->execute(
                array(
                    ":first" => $this->_first,
                    ":last" => $this->_last,
                    ":email" => $this->_email,
                    ":password" => $this->_password
                )
            );
            return $stmt->rowCount();
        }
        return 0;
    }
}

?>