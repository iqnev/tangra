<?php
/**
 *
 * @author iqnev
 */
namespace TG\Sessions;

interface iSession
{
    public function getSessionId();
    public function saveSession();
    public function removeSession();
    public function __get($name);
    public function __set($name, $value);
}
