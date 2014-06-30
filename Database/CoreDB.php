<?php


/**
 * Description of CoreDB
 *
 * @author iqnev
 */
namespace TG\Database;

class CoreDB
{
    protected  $connection = 'default';
    private  $db = null;
    private $stmt = null;
    private $sql = null;
    private $params = null;


    public function __construct($connection = 'default')
    {
        if($connection instanceof \PDO) {
            $this->db = $connection;
        } else if($connection != null) {
            $this->db = \TG\App::getInstance()->getDbConnection($connection);
            $this->connection = $connection;
        } else {
            $this->db = \TG\App::getInstance()->getDbConnection($connection);
        }
    }
    
    /**
     * 
     * @param type $sql
     * @param type $params
     * @param type $options
     * @return \TG\Database\CoreDB
     */
    public function prepare($sql, $params = [], $options = [])
    {
        $this->stmt = $this->db->prepare($sql, $options);
        $this->params = $params;
        $this->sql = $sql;
        
        return $this;
    }
    
    /**
     * 
     * @param type $params
     * @return \TG\Database\CoreDB
     */
    public function execute($params = [])
    {
        if($params) {
            $this->params = $params;
        }
        $this->stmt->execute($this->params);
        
        return$this;
    }
    
    /**
     * 
     * @return type
     */
    public function fetchAllAssoc(){
        return $this->stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * 
     * @return type
     */
    public function fetchRowAssoc(){
        return $this->stmt->fetch(\PDO::FETCH_ASSOC);    
    }
    
    /**
     * 
     * @return type
     */
    public function fetchAllNum(){
        return $this->stmt->fetchAll(\PDO::FETCH_NUM);    
    }
    
    /**
     * 
     * @return type
     */
    public function fetchAllObj(){
        return $this->stmt->fetchAll(\PDO::FETCH_OBJ);    
    }
    
    /**
     * 
     * @return type
     */
    public function fetchRowObj(){
        return $this->stmt->fetch(\PDO::FETCH_OBJ);    
    }
    
    /**
     * 
     * @return type
     */
    public function fetchAllColumn($column){
        return $this->stmt->fetchAll(\PDO::FETCH_COLUMN, $column);    
    }
    
    /**
     * 
     * @return type
     */
    public function getAffectedRows(){
        return $this->stmt->rowCount();    
    }
    
    /**
     * 
     * @return type
     */
    public function getLastInserId(){
        return $this->db->lastInsertid();    
    }
}

