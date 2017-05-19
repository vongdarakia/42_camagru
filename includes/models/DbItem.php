<?php 
/**
 * DbItem class file
 *
 * PHP version 5.5.38
 *
 * @category  DbItem
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   No License
 * @link      localhost:8080
 */

defined("DB_UINT")
or define("DB_UINT", 'Unsigned Int');

defined("DB_INT")
or define("DB_INT", 'Int');

defined("DB_INT_ID")
or define("DB_INT_ID", 'Integer ID');

defined("DB_STRING")
or define("DB_STRING", 'String');



/**
 * A generic class to hold general data and methods.
 *
 * @category  Class
 * @package   DbItem
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   No License
 * @link      localhost:8080
 */
class DbItem
{
    protected $db;
    protected $id;
    protected $table;
    protected $creation_date;
    
    /**
     * Constructs a user object given some values.
     *
     * @param DBConnectionObject $db    Database object we'll be using
                                        to access user data.
     * @param Array              $table The db table we're using.
     */
    function __construct($db, $table)
    {
        if (!isset($db)) {
            throw new Exception("Db must be set.", 1);
        }
        $this->db = $db;
        $this->id = 0;
        $this->table = $table;
    }

    /**
     * Sets the id of the item. Id must be greater than 0.
     *
     * @param String $value id of the item.
     *
     * @return Boolean whether set was successful or not.
     */
    public function setId($value)
    {
        if (validPositiveInt($value)) {
            $this->id = $value;
            return true;
        }
        return false;
    }

    /**
     * Gets the id of the item.
     *
     * @return String the id.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Sets the creation_date of the item.
     *
     * @param String $value creation_date of the item.
     *
     * @return Boolean whether set was successful or not.
     */
    public function setCreationDate($value)
    {
        $this->creation_date = $value;
        return true;
    }

    /**
     * Gets the creation_date of the item.
     *
     * @return String the creation_date.
     */
    public function getCreationDate()
    {
        return $this->creation_date;
    }

    /**
     * Gets a db object given the id. It's an object with all the
     * fields.
     *
     * @param Array $id ID of the item we're trying to get.
     *
     * @return Null or an object of the item.
     */
    public function getById($id)
    {
        $stmt = $this->db->prepare("select * from `{$this->table}` where id=:id");
        $stmt->execute(array(":id" => $id));
        return $stmt->fetchObject();
    }

    /**
     * Removes a user from the database given the id;
     *
     * @param Int $id The id of the user to be removed.
     *
     * @return Int number of users removed. Will be 1 or 0.
     */
    public function removeById($id)
    {
        $stmt = $this->db->prepare("delete from `{$this->table}` where id=:id");
        $stmt->execute(array(":id" => $id));
        return $stmt->rowCount();
    }

    /**
     * Removes the item from the database.
     *
     * @return Int number of item removed. Will be 1 or 0.
     */
    public function remove()
    {
        return $this->removeById($this->id);
    }

    /**
     * Checks fields if they are valid. Returns 0 the moment it finds
     * an invalid field.
     *
     * @param Array $fields       Fields to be validated.
     * @param Array $class_fields Fields to validate against.
     *
     * @return Int number of valid fields.
     */
    public function validFields($fields, $class_fields)
    {
        $count = 0;
        $checkedFields = [];
        if (is_array($fields)) {
            foreach ($fields as $field => $val) {
                if (!in_array($field, $class_fields)) {
                    return 0;
                }
                if (!in_array($field, $checkedFields)) {
                    $checkedFields[] = $field;
                    $count += 1;
                }
            }
        }
        return $count;
    }

    /**
     * Checks if the value is a positive non-zero number.
     * This is used for fields like IDs.
     *
     * @param Array $val value to be checked.
     *
     * @return Boolean if valid.
     */
    public static function validPositiveInt($val)
    {
        return is_numeric($val) && $val > 0;
    }

    /**
     * Checks if the value is a non-empty string.
     * This is used for fields like emails and passwords.
     *
     * @param Array $val value to be checked.
     *
     * @return Boolean if valid.
     */
    public static function validNonEmptyString($val)
    {
        return is_string($val) && $val != "";
    }

    /**
     * Gets an object containing information on the given query. The information
     * has a list of items depending on the limit (x items per page) and the page
     * number. If limit is set to 'all', it will not apply a limit and will grab
     * all data available.
     *
     * @param Int    $page  Which page of items we're trying to get.
     * @param Int    $limit How many items per page we want to see.
     * @param String $query Query we're applying a limit to.
     *
     * @return Object
     *      $page  -> Page number
     *      $limit -> Number of items per page
     *      $total -> Total items from table
     *      $rows  -> List of items retrieved for the page.
     */
    public function getDataByPage($page=1, $limit=10, $query=null)
    {
        if ($page <= 0 || $limit <= 0) {
            throw new Exception("Error: page and limit must be positive number", 1);
        }
        if ($query === null) {
            $query = "select * from $this->table";
        }

        $countQuery = "select count(1) from $this->table";
        $rows = $this->db->query($countQuery);
        $count = $rows->fetchColumn();

        if ($limit != 'all') {
            $query = "$query limit ". ($limit * ($page - 1)) .", $limit";
        }

        $rows = $this->db->query($query);

        $info         = new stdClass();
        $info->page   = $page;
        $info->limit  = $limit;
        $info->total  = $count;
        $info->rows   = $rows;
        return $info;
    }
}

?>