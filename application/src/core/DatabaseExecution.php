<?php


namespace Core;


/**
 * Class DatabaseExecution
 * @package Core
 */
class DatabaseExecution
{
    /**
     * @var \MySQLi
     */
    public $db;

    /**
     * DatabaseExecution constructor.
     */
    public function __construct()
    {
        $this->db = Database::getInstance()->db;

    }

    /**
     * @param string $sql
     * @return object|null
     * @throws \Exception
     */
    public function query(string $sql): ?object
    {
        $query = $this->db->query($sql);

        if (!$this->db->errno) {
            if ($query instanceof \mysqli_result) {
                $data = array();

                while ($row = $query->fetch_assoc()) {
                    $data[] = $row;
                }

                $result = new \stdClass();
                $result->num_rows = $query->num_rows;
                $result->row = isset($data[0]) ? $data[0] : array();
                $result->rows = $data;

                $query->close();

                return $result;
            } else {
                return null;
            }
        } else {
            throw new \Exception('Error: ' . $this->db->error . '<br />Error No: ' . $this->db->errno . '<br />' . $sql);
        }
    }

    /**
     * @return int|string
     */
    public function insert_id()
    {
        return $this->db->insert_id;
    }
}