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
 * @license   Akia's Public License
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
 * @license   Akia's Public License
 * @link      localhost:8080
 */
class DbItem
{
    protected $db;
    protected $id;
    protected $table;
    
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
     * Sets the id of the user. Id must be greater than 0.
     *
     * @param String $value id of the user.
     *
     * @return Boolean whether set was successful or not.
     */
    public function setId($value)
    {
        if (isset($value) && $value > 0) {
            $this->id = $value;
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
        return $this->id;
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


    public function getDataByPage($query, $page = 1, $limit = 10)
    {
        $start = microtime(true);
        // $stmt = $this->db->prepare("select count(id) as 'count' from `user`");
        // $stmt->execute();
        // $obj = $stmt->fetchObject();
        // if ($obj)
        //     $result->total = $obj->count;
        // else
        //     $result->total = 0;
        // $result->total  = $this->_totalRows;
        
        $countQuery = "select count(1) from $this->table";
        
        $rows = $this->db->query($countQuery);
        $count = $rows->fetchColumn();
        echo "total " . $rows->fetchColumn() . PHP_EOL;

        if ($limit != 'all') {
            $query = "$query limit ". ($limit * ($page - 1)) .", $limit";
        }
        $rows = $this->db->query($query);
        // $stmt->execute();

        // $stmt = $this->db->prepare();

        $end = microtime(true);
        $diff = $end - $start;
        echo "time: " . $diff . PHP_EOL;

        // $results = $this->_conn->query($query);

        $result         = new stdClass();
        $result->page   = $page;
        $result->limit  = $limit;
        $result->total  = $count;
        $result->rows   = $rows;

        foreach ($rows as $value) {
            echo $value['first'] . PHP_EOL;
        }
        return 0;
    }
}

?>