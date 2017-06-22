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
    private $_img_file;
    private $_title;
    private $_description;
    private static $_fields = [
        "id",
        "author_id",
        "img_file",
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
        $this->_img_file = "";
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
    public function getImgFile()
    {
        return $this->_img_file;
    }

    /**
     * Sets the image name.
     *
     * @param String $value image name.
     *
     * @return Boolean whether set was successful or not.
     */
    public function setImgFile($value)
    {
        if (DbItem::validNonEmptyString($value)) {
            $this->_img_file = $value;
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
                img_file=:img_file,
                description=:description,
            where id=:id";
        $stmt = $this->db->prepare($qry);
        $stmt->execute(
            array(
                ":author_id" => $this->_author_id,
                ":title" => $this->_title,
                ":img_file" => $this->_img_file,
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
            $this->_img_file = $result->img_file;
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
            if (array_key_exists('img_file', $fields)) {
                if (!$this->setImgFile($fields['img_file'])) {
                    throw new Exception("img_file can't be empty.", 1);
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
                } else if (array_key_exists('img_file', $fields)) {
                    if (!(isset($fields['img_file'])
                        && !empty($fields['img_file']))
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
     * @param Int $fields Values we're adding.
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
                (author_id, title, img_file, description)
                values (:author_id, :title, :img_file, :description)"
            );
            $stmt->execute(
                array(
                    ":author_id" => $this->_author_id,
                    ":title" => $this->_title,
                    ":img_file" => $this->_img_file,
                    ":description" => $this->_description
                )
            );
            if ($stmt->rowCount() == 1) {
                return $this->db->lastInsertId();
            }
        }
        return false;
    }

    /**
     * Gets all comments for this post.
     *
     * @param Int $id Post ID
     *
     * @return PDO Object of all comments, or false if failed.
     */
    public function getComments($id=null)
    {
        if ($id == null) {
            $id = $this->id;
        }
        $query = "
            select
                c.id 'id',
                u.username 'author_login',
                c.comment 'comment',
                c.creation_date 'creation_date'
            from `{$this->table}` p
            inner join `comment` c on c.post_id = p.id
            inner join `user` u on c.author_id = u.id
            where p.id = " . $id . "
            order by c.creation_date desc, c.id asc";

        $rows = $this->db->query($query);
        return $rows;
    }

    /**
     * Gets all comments for this post.
     *
     * @param Int $id Post ID
     *
     * @return PDO Object of all comments, or false if failed.
     */
    public function getNumLikes($id=null)
    {
        if ($id == null) {
            $id = $this->id;
        }
        $query = "
            select count(p.id) 'count'
            from `{$this->table}` p
            inner join `like` l on l.post_id = p.id
            inner join `user` u on u.id = l.author_id
            where p.id = " . $id . "
            group by p.id";

        $stmt = $this->db->prepare($query);
        $stmt->execute();

        $count = $stmt->fetchColumn();
        if ($count != false || $count != null) {
            return $count;
        }
        return 0;
    }

    /**
     * Gets all comments for this post.
     *
     * @param String $user_email User's email
     * @param Int    $id         Post ID
     *
     * @return PDO Object of all comments, or false if failed.
     */
    public function didUserLike($user_email, $id=null)
    {
        if ($id == null) {
            $id = $this->id;
        }
        $query = "
            select distinct 1 'liked'
            from `like` l
            inner join `user` u on u.id = l.author_id 
            inner join `post` p on p.id = l.post_id
            where
                u.email=:user_email and
                p.id=:post_id";

        $stmt = $this->db->prepare($query);
        $stmt->execute(array(':user_email' => $user_email, ':post_id' => $id));

        $count = $stmt->fetchColumn();
        return $count == 1;
    }
}

?>