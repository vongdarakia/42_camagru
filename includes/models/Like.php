<?php 
/**
 * Like class file
 *
 * PHP version 5.5.38
 *
 * @category  Like
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   Akia's Public License
 * @link      localhost:8080
 */

require_once 'DbItem.php';

/**
 * Like class that holds all its database operations. This
 * manages when a user likes a post.
 *
 * @category  Class
 * @package   Like
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   Akia's Public License
 * @link      localhost:8080
 */
class Like extends DbItem
{
    private $_author_id;
    private $_post_id;
    private static $_fields = ["id", "author_id", "post_id"];
    
    /**
     * Constructs a post object given some values.
     *
     * @param DBConnectionObject $db     Database object we'll be using
                                         to access post data.
     * @param Array              $fields Fields we're setting for the object.
     */
    function __construct($db, $fields=null)
    {
        parent::__construct($db, 'like');
        $this->_author_id = 0;
        $this->_post_id = "";
        if (isset($fields)) {
            if ($this->validFields($fields, Like::$_fields)) {
                $this->setFields($fields);
            } else {
                throw new Exception("Invalid fields.", 1);
            }
        }
    }

    /**
     * Gets the author_id.
     *
     * @return Int the author_id.
     */
    public function getAuthorId()
    {
        return $this->_author_id;
    }

    /**
     * Sets the author_id.
     *
     * @param Int $value First name of the post.
     *
     * @return Boolean whether set was successful or not.
     */
    public function setAuthorId($value)
    {
        if (isset($value) && $value > 0) {
            $this->_author_id = $value;
            return true;
        }
        return false;
    }

    /**
     * Gets the post_id.
     *
     * @return Int the post_id.
     */
    public function getPostId()
    {
        return $this->_post_id;
    }

    /**
     * Sets the post_id.
     *
     * @param Int $value post_id.
     *
     * @return Boolean whether set was successful or not.
     */
    public function setPostId($value)
    {
        if (isset($value) && $value > 0) {
            $this->_post_id = $value;
            return true;
        }
        return false;
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
                author_id=:author_id,
                post_id=:post_id,
            where id=:id";
        $stmt = $this->db->prepare($qry);
        $stmt->execute(
            array(
                ":author_id" => $this->_author_id,
                ":post_id" => $this->_post_id,
                ":id" => $this->id
            )
        );
        return $stmt->rowCount();
    }

    /**
     * Loads the data to this object given the ID.
     *
     * @param Int $id ID of the we're trying to get.
     *
     * @return Boolean on whether it was successful or not.
     */
    public function loadById($id)
    {
        $result = $this->getById($id);
        if ($result) {
            $this->id = $result->id;
            $this->_author_id = $result->author_id;
            $this->_post_id = $result->post_id;
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
            if (array_key_exists('author_id', $fields)) {
                if (!$this->setAuthorId($fields['author_id'])) {
                    throw new Exception("author_id must be greater than 0.", 1);
                }
            }
            if (array_key_exists('post_id', $fields)) {
                if (!$this->setPostId($fields['post_id'])) {
                    throw new Exception("post_id must be greater than 0.", 1);
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
                    if (!(isset($fields['id']) && $fields['id'] <= 0)) {
                        return false;
                    }
                } else if (array_key_exists('author_id', $fields)) {
                    if (!(isset($fields['author_id'])
                        && $fields['author_id'] <= 0)
                    ) {
                        return false;
                    }
                } else if (array_key_exists('post_id', $fields)) {
                    if (!(isset($fields['post_id'])
                        && $fields['post_id'] <= 0)
                    ) {
                        return false;
                    }
                }
                return true;
            }
        }
        return false;
    }

    /**
     * Adds a post to the database given a list of fields. Must have all 3 values.
     *
     * @param Array $fields Values of the posts we're adding.
     *
     * @return Int number of posts added. Will be 1 or 0.
     */
    public function add($fields)
    {
        if ($this->validFields($fields, Like::$_fields) == count(Like::$_fields) - 1
            && $this->setFields($fields)
        ) {
            print("valid");
            $stmt = $this->db->prepare(
                "insert into `{$this->table}` (author_id, post_id)
                values (:author_id, :post_id)"
            );
            $stmt->execute(
                array(
                    ":author_id" => $this->_author_id,
                    ":post_id" => $this->_post_id
                )
            );
            return $stmt->rowCount();
        }
        return 0;
    }
}

?>