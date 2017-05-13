<?php 
/**
 * Post class file
 *
 * PHP version 5.5.38
 *
 * @category  Post
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   Akia's Public License
 * @link      localhost:8080
 */

/**
 * Post class that holds all its database operations. This is the
 * image post by a user. Other users can like or comment on it.
 *
 * @category  Class
 * @package   Post
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   Akia's Public License
 * @link      localhost:8080
 */
class DbItem
{
    private $_db;
    private $_id;
    
    /**
     * Constructs a user object given some values.
     *
     * @param DBConnectionObject $db     Database object we'll be using
                                         to access user data.
     * @param Array              $fields Fields we're setting for the object.
     */
    function __construct($db, $fields)
    {
        if (!isset($db)) {
            throw new Exception("Db must be set.", 1);
        }
        $this->_db = $db;
        $this->_id = 0;
    }

    /**
     * Sets the id of the user. Id must be greater than 0.
     *
     * @param String $value id of the user.
     *
     * @return Boolean whether set was successful or not.
     */
    public function setId($value)
    {
        if (isset($value) && $value > 0) {
            $this->_id = $value;
            return true;
        }
        return false;
    }

    /**
     * Gets the id of the user.
     *
     * @return String the id.
     */
    public function getId()
    {
        return $this->_id;
    }
}

?>