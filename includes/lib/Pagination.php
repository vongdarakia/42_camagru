<?php 

/**
* 
*/
class Pagination
{
    private $_conn;
    private $_limit;
    private $_page;
    private $_query;
    private $_totalRows;

    function __construct($conn, $query)
    {
        $this->_conn = $conn;
        $this->_query = $query;

        $stmt = $conn->prepare($query);
        $stmt->execute();
        $this->_totalRows = $stmt->rowCount();
    }

    public function getData($limit = 10, $page = 1)
    {
        $this->_limit = $limit;
        $this->_page = $page;

        if ($this->_limit == 'all') {
            $query = $this->_query;
        } else {
            $query = "$this->_query limit ". ($limit * ($page - 1)) .", $limit";
        }

        $results = $this->_conn->query($query);

        $result         = new stdClass();
        $result->page   = $this->_page;
        $result->limit  = $this->_limit;
        $result->total  = $this->_totalRows;
        $result->rows   = $results;
        return $result;
    }
}
// require_once '../../config/connect.php';
// $Pagination = new Pagination($dbh, "select * from `user`");
// $data = $Pagination->getData();
// foreach ($data->rows as $value) {
//     echo $value['first'] . PHP_EOL;
// }
?>