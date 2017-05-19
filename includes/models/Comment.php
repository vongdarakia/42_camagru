<?php 
/**
 * Comment class file
 *
 * PHP version 5.5.38
 *
 * @category  Comment
 * @package   Camagru
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   No License
 * @link      localhost:8080
 */

require_once 'DbItem.php';

/**
 * Comment class that holds all its database operations. This is the
 * comment created by a user on a post.
 *
 * @category  Class
 * @package   Comment
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   No License
 * @link      localhost:8080
 */
class Comment extends DbItem
{
    private $_author_id;
    private $_img_name;
    private $_title;
    private $_description;
    private static $_fields = ["id", "author_id", "post_id", "comment"];
    
    /**
     * Constructs a post object given some values.
     *
     * @param DBConnectionObject $db     Database object we'll be using
                                         to access post data.
     * @param Array              $fields Fields we're setting for the object.
     */
    function __construct($db, $fields=null)
    {
        parent::__construct($db, 'comment');
        $this->_author_id = 0;
        $this->_post_id = 0;
        $this->_comment = "";
        if (isset($fields)) {
            if ($this->validFields($fields, Comment::$_fields)) {
                $this->setFields($fields);
            } else {
                throw new Exception("Invalid fields.", 1);
            }
        }
    }

    /**
     * Gets the author id.
     *
     * @return Int the author id.
     */
    public function getAuthorId()
    {
        return $this->_author_id;
    }

    /**
     * Sets the author_id.
     *
     * @param Int $value author id.
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
     * Gets the post id.
     *
     * @return Int the post id.
     */
    public function getPostId()
    {
        return $this->_post_id;
    }

    /**
     * Sets the post_id.
     *
     * @param Int $value post id.
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
     * Gets the comment.
     *
     * @return String the comment.
     */
    public function getComment()
    {
        return $this->_comment;
    }

    /**
     * Sets the comment.
     *
     * @param String $value comment.
     *
     * @return Boolean whether set was successful or not.
     */
    public function setComment($value)
    {
        if (isset($value) && !empty($value)) {
            $this->_comment = $value;
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
                comment=:comment
            where id=:id";
        $stmt = $this->db->prepare($qry);
        $stmt->execute(
            array(
                ":author_id" => $this->_author_id,
                ":post_id" => $this->_post_id,
                ":comment" => $this->_comment,
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
            $this->_comment = $result->comment;
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
            if (array_key_exists('comment', $fields)) {
                if (!$this->setComment($fields['comment'])) {
                    throw new Exception("comment can't be empty.", 1);
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
                } else if (array_key_exists('comment', $fields)) {
                    if (!(isset($fields['comment'])
                        && !empty($fields['comment']))
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
     * @param Int $fields Valuess we're adding.
     *
     * @return Int number of posts added. Will be 1 or 0.
     */
    public function add($fields)
    {
        $numFields = count(Comment::$_fields) - 1;
        if ($this->validFields($fields, Comment::$_fields) == $numFields
            && $this->setFields($fields)
        ) {
            $stmt = $this->db->prepare(
                "insert into `{$this->table}` (author_id, post_id, comment)
                values (:author_id, :post_id, :comment)"
            );
            $stmt->execute(
                array(
                    ":author_id" => $this->_author_id,
                    ":post_id" => $this->_post_id,
                    ":comment" => $this->_comment
                )
            );
            return $stmt->rowCount();
        }
        return 0;
    }
}

?>