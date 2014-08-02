<?php

/**
 * Description of DbSession
 *
 * @author iqnev
 */

namespace TG\Sessions;

class DbSession extends \TG\Database\CoreDB implements \TG\Sessions\iSession {

    private $sessionName;
    private $dbTable;
    private $expiration;
    private $path;
    private $domain;
    private $security;
    private $sessionId = null;
    private $sessionData = [];

    public function __construct($dbConnection, $name, $dbTable = 'sessions', $expiration = 3600, $path = null, $domain = null, $security = false)
    {
        parent::__construct($dbConnection);

        $this->sessionName = $name;
        $this->dbTable = $dbTable;
        $this->expiration = $expiration;
        $this->path = $path;
        $this->domain = $domain;
        $this->sessionId = $_COOKIE[$name];
        $this->security = $security;

        if (strlen($this->sessionId < 32)) {
            $this->newASession();
        } elseif (!$this->validateSession()) {
            $this->newSession();
        }
        
        if(rand(0,100)) {
            $this->garbageCollector();
        }
    }

    private function newASession()
    {
        // TODO make best generation function
        $this->sessionId = md5(uniqid('fortran'));
        $this->prepare('INSERT INTO ' . $this->dbTable . ' (sessid, valid_until) VALUE(?, ?)', [
            $this->sessionId, (time() + $this->expiration)])->execute();
        setcookie($this->sessionName, $this->sessionId, (time() + $this->expiration), $this->path, $this->domain, $this->security, true);
    }
    
    private function garbageCollector()
    {
        $this->prepare('DELETE FROM ' . $this->dbTable . 'WHERE valid_until<?', [time()])->execute();
    }

    private function validateSession()
    {
        if ($this->sessionId) {
            $result = $this->prepare('SELECT * FROM ' . $this->dbTable . ' WHERE sessid=? AND valid_until<=?', [
                        $this->sessionId, (time() + $this->expiration)])->execute()->fetchAllAssoc();

            if (is_array($result) && $result[0]) {
                $this->sessionData = unserialize($resultp[0]['sess_data']);

                return true;
            }
        }

        return false;
    }

    public function __get($name)
    {
        return $this->sessionData[$name];
    }

    public function __set($name, $value)
    {       
        $this->sessionData[$name] = $value;
    }

    public function getSessionId()
    {
        return $this->sessionId;
    }

    public function removeSession()
    {
        if($this->sessionId) {
            $this->prepare('DELETE FROM ' . $this->dbTable . ' WHERE sessid=?', [
                $this->sessionId
            ])->execute();
        }
    }

    public function saveSession()
    {        
        if ($this->sessionId) {
            $this->prepare('UPDATE ' . $this->dbTable . ' SET sess_data=?, valid_until=? WHERE sessid=?', [
                serialize($this->sessionData), (time() + $this->expiration), $this->sessionId
            ])->execute();
            setcookie($this->sessionName, $this->sessionId, (time() + $this->expiration), $this->path, $this->domain, $this->security, true);
        }
    }

}
