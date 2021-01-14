<?php

namespace TB;

require_once 'config.php';
require_once 'log-script.php';

class Administrator {
    public function __construct(array $configini) {
        $this->_dbconfig = $configini['db'];
        $this->_administrator = $configini['administrator'];

        $this->_driver = $this->_dbconfig['driver'];
        $this->_host = $this->_dbconfig['host'];
        $this->_username = $this->_dbconfig['username'];
        $this->_password = $this->_dbconfig['password'];

        $this->_dbname = $this->_administrator['db_name'];
        $this->_tablename = $this->_administrator['db_table'];
        $this->_password_hash_algo = $this->_administrator['password_hash_algo'];
        $this->_superuser = $this->_administrator['super_username'];
        $this->_superpass = $this->_administrator['super_password'];

        $this->open();
    }
    private $_dbconfig;
    private $_driver;
    private $_host;
    private $_username;
    private $_password;
    private $_options = [
        \PDO::ATTR_PERSISTENT => true
    ];
    private $_dbname;
    private $_tablename;
    private $_password_hash_algo;
    private $_superuser;
    private $_superpass;
    
    private $_administrator;

    private static ?\PDO $_connection = null;

    public static bool $isInit = false;

    public function open(): bool {
        if  (is_null(static::$_connection)) {
            try {
             static::$_connection = new \PDO($this->_driver . $this->_host."dbname=$this->_dbname", $this->_username, $this->_password, $this->_options);
             static::$_connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
             
             return true;
            } catch (\PDOException $error) {
                die("Unable to connect: " . $error->getMessage());
            }
        } else {
            return true;
        }
    }
    public function insert(array $data): bool {
        if  (static::$_connection) {
            try {
                $statement = static::$_connection->prepare("INSERT INTO $this->_tablename (
                    username,
                    password,
                    email,
                    signup_id,
                    session_id,
                    algo_hash
                ) VALUES (?, ?, ?, ?, ?, ?)");

                $algoHash = password_hash($data['password'], $this->_password_hash_algo);

                $statement->bindParam(1, $data['username'], \PDO::PARAM_STR);
                $statement->bindParam(2, $data['password'], \PDO::PARAM_STR);
                $statement->bindParam(3, $data['email'], \PDO::PARAM_STR);
                $statement->bindParam(4, $data['signup_id'], \PDO::PARAM_STR);
                $statement->bindParam(5, $data['session_id'], \PDO::PARAM_STR);
                $statement->bindParam(6, $algoHash, \PDO::PARAM_STR);
                
                static::$_connection->beginTransaction();

                $statement->execute();

                return static::$_connection->commit();
            } catch (\Throwable $th) {
                static::$_connection->rollBack();

                logToScript($th);

                return false;
            }
        } else {
            logToScript('database administrator connection closed');

            return false;
        }
    }
    public function updateSession(int $id, ?string $value) {
        return $this->_updateTerminal('session_id = ?', 'id = ?', [$value, $id], [\PDO::PARAM_STR, \PDO::PARAM_INT]);
    }
    public function genDefaultData(array $data) {
        foreach ($data as $key => $value) {
            $initialData[$key] = htmlentities($value);
        }
        return array_merge([
            'username' => null,
            'password' => null,
            'email' => null,
            'sign_id' => null,
            'session_id' => null
        ], $initialData);
    }
    public function genDefaultTypeData() {
        return [
            \PDO::PARAM_STR,
            \PDO::PARAM_STR,
            \PDO::PARAM_STR,
            \PDO::PARAM_STR,
            \PDO::PARAM_STR
        ];
    }

    /** 
     * @deprecated
     */
    public function get(string $username, string $password, bool $esc = false): ?array {
        if (static::$_connection) {
            try {
                $sql = "SELECT * FROM $this->_tablename WHERE username = ? AND password = ?";

                $statement = static::$_connection->prepare($sql);
                $statement->bindParam(1, $username, \PDO::PARAM_STR);
                $statement->bindParam(2, $esc ? $password : password_hash($password, $this->_password_hash_algo), \PDO::PARAM_STR);
                    
                $isSuccess = $statement->execute();
                    
                if ($isSuccess) {
                    $statement->setFetchMode(\PDO::FETCH_ASSOC);
                    
                    $result = $statement->fetchAll();
                
                    if ($result) {
                        return $result[0];
                    } else {
                        return null;
                    }
                } else {
                    return null;
                }
            } catch (\Throwable $th) {
                logToScript($th);

                return null;
            }
        } else {
            logToScript('database administrator connection closed');

            return null;
        }
    }

    public function getByUsername(string $username): ?array {
        return $this->_getTerminal('*', "username = ?", [$username]);
    }
    public function getBySession(string $id): ?array {
        return $this->_getTerminal('*', "session_id = ?", [$id]);
    }
    public function getBySignup(string $id): ?array {
        return $this->_getTerminal('*', "signup_id = ?", [$id]);
    }
    
    private function _getTerminal(string $columns = '*', string $condition = '1', array $value = [], bool $all = false): ?array {
        if (static::$_connection) {
            try {

                $sql = "SELECT $columns FROM $this->_tablename WHERE $condition";

                $statement = static::$_connection->prepare($sql);

                $index = 0;
                $type = $this->genDefaultTypeData();

                foreach ($value as $key => $value) {
                    if (is_int($key)) {
                        ++$key;
                    }
                    $statement->bindParam($key, $value, $type[$index]);
                    $index++;
                }
                    
                $isSuccess = $statement->execute();
                    
                if ($isSuccess) {
                    $statement->setFetchMode(\PDO::FETCH_ASSOC);
                    
                    $result = $statement->fetchAll();
                
                    if ($result) {
                        if ($all) {
                            return $result;
                        } else {
                            return $result[0];
                        }
                    } else {
                        return null;
                    }
                } else {
                    return null;
                }
            } catch (\Throwable $th) {
                logToScript($th);

                return null;
            }
        } else {
            logToScript('database administrator connection closed');

            return null;
        }
    }

    private function _updateTerminal(string $column, string $condition, array $value, array $type): bool {
        if (static::$_connection) {
            try {
                $sql = "UPDATE $this->_tablename SET $column WHERE $condition";

                $statement = static::$_connection->prepare($sql);

                foreach ($value as $key => $value) {
                    $tmpType = $type[$key];

                    if (is_int($key)) {
                        ++$key;
                    }
                    $statement->bindValue($key, $value, $tmpType);
                }
                
                static::$_connection->beginTransaction();

                $statement->execute();

                return static::$_connection->commit();
            } catch (\Throwable $th) {
                static::$_connection->rollBack();

                logToScript($th);

                return false;
            }
        } else {
            logToScript('database administrator connection closed');

            return null;
        }
    }
}
