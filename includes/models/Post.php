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
 * @license   No License
 * @link      localhost:8080
 */

require_once 'DbItem.php';

/**
 * Post class that holds all its database operations. This is the
 * image post by a user. Other users can like or comment on it.
 *
 * @category  Class
 * @package   Post
 * @author    Akia Vongdara <vongdarakia@gmail.com>
 * @copyright 2017 Akia Vongdara
 * @license   No License
 * @link      localhost:8080
 */
class Post extends DbItem
{
    private $_author_id;
    private $_img_name;
    private $_title;
    private $_description;
    private static $_fields = [
        "id",
        "author_id",
        "img_name",
        "title",
        "description"
    ];
    
    /**
     * Constructs a post object given some values.
     *
     * @param DBConnectionObject $db     Database object we'll be using
                                         to access post data.
     * @param Array              $fields Fields we're setting for the object.
     */
    function __construct($db, $fields=null)
    {
        parent::__construct($db, 'post');
        $this->_author_id = 0;
        $this->_img_name = "";
        $this->_title = "";
        $this->_description = "";
        if (isset($fields)) {
            if ($this->validFields($fields, Post::$_fields)) {
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
        if (DbItem::validPositiveInt($value)) {
            $this->_author_id = $value;
            return true;
        }
        return false;
    }

    /**
     * Gets the image name.
     *
     * @return String the image name.
     */
    public function getImgName()
    {
        return $this->_img_name;
    }

    /**
     * Sets the image name.
     *
     * @param String $value image name.
     *
     * @return Boolean whether set was successful or not.
     */
    public function setImgName($value)
    {
        if (DbItem::validNonEmptyString($value)) {
            $this->_img_name = $value;
            return true;
        }
        return false;
    }

    /**
     * Gets the title.
     *
     * @return String the title.
     */
    public function getTitle()
    {
        return $this->_title;
    }

    /**
     * Sets the title.
     *
     * @param String $value title.
     *
     * @return Boolean whether set was successful or not.
     */
    public function setTitle($value)
    {
        if (DbItem::validNonEmptyString($value)) {
            $this->_title = $value;
            return true;
        }
        return false;
    }

    /**
     * Gets the description.
     *
     * @return String the description.
     */
    public function getDescription()
    {
        return $this->_description;
    }

    /**
     * Sets the description.
     *
     * @param String $value description.
     *
     * @return Boolean whether set was successful or not.
     */
    public function setDescription($value)
    {
        if (is_string($value)) {
            $this->_description = $value;
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
                title=:title,
                img_name=:img_name,
                description=:description,
            where id=:id";
        $stmt = $this->db->prepare($qry);
        $stmt->execute(
            array(
                ":author_id" => $this->_author_id,
                ":title" => $this->_title,
                ":img_name" => $this->_img_name,
                ":description" => $this->_description,
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
            $this->_title = $result->title;
            $this->_img_name = $result->img_name;
            $this->_description = $result->description;
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
                    throw new Exception("author_id must be greater than 0", 1);
                }
            }
            if (array_key_exists('title', $fields)) {
                if (!$this->setTitle($fields['title'])) {
                    throw new Exception("title can't be empty.", 1);
                }
            }
            if (array_key_exists('img_name', $fields)) {
                if (!$this->setImgName($fields['img_name'])) {
                    throw new Exception("img_name can't be empty.", 1);
                }
            }
            if (array_key_exists('description', $fields)) {
                if (!$this->setDescription($fields['description'])) {
                    throw new Exception("description can't be empty.", 1);
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
                } else if (array_key_exists('img_name', $fields)) {
                    if (!(isset($fields['img_name'])
                        && !empty($fields['img_name']))
                    ) {
                        return false;
                    }
                } else if (array_key_exists('description', $fields)) {
                    if (!(isset($fields['description'])
                        && !empty($fields['description']))
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
     * @return Boolean whether the add was successful or not.
     */
    public function add($fields)
    {
        if ($this->validFields($fields, Post::$_fields) == count(Post::$_fields) - 1
            && $this->setFields($fields)
        ) {
            $stmt = $this->db->prepare(
                "insert into `{$this->table}`
                (author_id, title, img_name, description)
                values (:author_id, :title, :img_name, :description)"
            );
            $stmt->execute(
                array(
                    ":author_id" => $this->_author_id,
                    ":title" => $this->_title,
                    ":img_name" => $this->_img_name,
                    ":description" => $this->_description
                )
            );
            return $stmt->rowCount() == 1;
        }
        return false;
    }
}

?>