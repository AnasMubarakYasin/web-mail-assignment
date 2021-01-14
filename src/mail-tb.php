<?php

namespace TB;

require_once 'config.php';

class Mail {
    public function __construct(array $configini) {
        $this->_dbconfig = $configini['db'];
        $this->_mail = $configini['mail'];

        $this->_driver = $this->_dbconfig['driver'];
        $this->_host = $this->_dbconfig['host'];
        $this->_username = $this->_dbconfig['username'];
        $this->_password = $this->_dbconfig['password'];

        $this->_dbname = $this->_mail['db_name'];
        $this->_tablename = $this->_mail['db_table']['mail_in'];

        $this->_columnInstitute = $this->_mail['post_institute'];
        $this->_columnNumber = $this->_mail['post_number'];
        $this->_columnSubject = $this->_mail['post_subject'];
        $this->_columnDate = $this->_mail['post_date'];
        $this->_columnFilename = $this->_mail['file_upload'];
        $this->_columnIsAlreadyRead = $this->_mail['is_already_read'];
        $this->_origin = $this->_mail['origin'];

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

    private $_mail;

    private $_columnInstitute;
    private $_columnNumber;
    private $_columnSubject;
    private $_columnDate;
    private $_columnFilename;
    private $_columnIsAlreadyRead;
    private $_origin;

    private ?\PDO $_connection = null;

    public function open() {
        if  ($this->_connection === null) {
            try {
                $this->_connection = new \PDO($this->_driver . $this->_host."dbname=$this->_dbname", $this->_username, $this->_password, $this->_options);
                $this->_connection->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
            } catch (\PDOException $error) {
                die("Unable to connect: " . $error->getMessage());
            }
        }
    }
    public function insert(array $data) {
        if  ($this->_connection) {
            try {
                $statement = $this->_connection->prepare("INSERT INTO $this->_tablename (
                    $this->_columnInstitute,
                    $this->_columnNumber,
                    $this->_columnSubject,
                    $this->_columnDate,
                    $this->_columnFilename
                ) VALUES (?, ?, ?, ?, ?)");
                $statement->bindParam(1, $data[$this->_columnInstitute]);
                $statement->bindParam(2, $data[$this->_columnNumber]);
                $statement->bindParam(3, $data[$this->_columnSubject]);
                $statement->bindParam(4, $data[$this->_columnDate]);
                $statement->bindParam(5, $data[$this->_columnFilename]);
                
                $this->_connection->beginTransaction();

                $statement->execute();

                return $this->_connection->commit();
            } catch (\Throwable $th) {
                $this->_connection->rollBack();

                return false;
            }
        }
    }

    /** 
     * @deprecated
     */
    public function get(?int $id, ?string $institute, ?string $number): ?array {
        $args = ['id' => $id, $this->_columnInstitute => "'$institute'", $this->_columnNumber => $number];
        $condition = '';
        $index = 1;
        $limit = count($args);

        foreach ($args as $key => $value) {
            $condition .= ($value !== null ? "$key = $value": '') . ($value !== null ? $index === $limit ? '' : ' AND ' : '');
            $index++;
        }

        $sql = "SELECT * FROM $this->_tablename WHERE $condition";


        $statement = $this->_connection->prepare($sql);

        $isSuccess = $statement->execute();

        if ($isSuccess) {
            $statement->setFetchMode(\PDO::FETCH_ASSOC);
            
            $result = $statement->fetchAll();

            if ($result) {
                return $result[0];
            } else {
                return [];
            }
        } else {
            return [];
        }
    }

    public function getCountMail(string $column = 'id', string $condition = '1', array $value = []): int {
        $key = 'count';
        $result = $this->_get(
            "COUNT($column) AS $key",
            $condition,
            $value
        );
        return (int) $result ? $result[$key] : $result;
    }
    public function getMailIn(): ?array {
        return $this->_get('*', "$this->_columnInstitute != ?", [$this->_origin], true);
    }
    public function getMailOut(): ?array {
        return $this->_get('*', "$this->_columnInstitute = ?", [$this->_origin], true);
    }
    public function getMailDeadline(): ?array {
        return $this->_get('*', "$this->_columnDate > NOW() ORDER BY $this->_columnDate", [], true);
    }
    public function getCountMailIn() {
        return $this->getCountMail('id', "$this->_columnInstitute != ?", [$this->_origin]);
    }
    public function getCountMailOut() {
        return $this->getCountMail('id', "$this->_columnInstitute = ?", [$this->_origin]);
    }
    public function getCountMailDeadline() {
        return $this->getCountMail('id', "$this->_columnDate > NOW()", []);
    }
    public function getCountAlreadyReadMail(string $condition, array $value = []) {
        $key = 'already_read';
        $data = $this->_get(
            "COUNT($this->_columnIsAlreadyRead) AS $key",
            "$condition AND $this->_columnIsAlreadyRead = FALSE",
            $value,
        );
        return ($data ? $data[$key] : $data);
    }
    public function getCountAlreadyReadMailIn() {
        return $this->getCountAlreadyReadMail("$this->_columnInstitute != ?", [$this->_origin]);
    }
    public function getCountAlreadyReadMailOut() {
        return $this->getCountAlreadyReadMail("$this->_columnInstitute = ?", [$this->_origin]);
    }
    public function getCountAlreadyReadMailDeadline() {
        $key = 'already_read';
        $data = $this->_get(
            "COUNT($this->_columnIsAlreadyRead) AS $key",
            "$this->_columnDate BETWEEN NOW() AND ADDTIME(DATE_ADD(CURRENT_DATE(), INTERVAL 2 DAY), MAKETIME(0, 0, 0))",
            [],
        );
        return ($data ? $data[$key] : $data);
        return $this->getCountAlreadyReadMail("");
    }
    public function searchOnMail(string $query, string $extraCondition, array $value = [], array $type = []) {
        $query = "%$query%";
        return $this->_get(
            "*",
            "$extraCondition AND (
                $this->_columnInstitute LIKE :query OR
                $this->_columnNumber LIKE :query OR
                $this->_columnSubject LIKE :query OR
                $this->_columnFilename LIKE :query
            )",
            array_merge(
                [
                    ':query'=> $query,
                    ':query'=> $query,
                    ':query'=> $query,
                    ':query'=> $query,
                ],
                $value
            ),
            true,
            array_merge(
                [
                    ':query' => \PDO::PARAM_STR,
                ],
                $type
            )
        );
    }
    public function searchOnMailIn(string $query) {
        return $this->searchOnMail($query, "$this->_columnInstitute != :extra", [':extra' =>  $this->_origin], [':extra' => \PDO::PARAM_STR]);
    }
    public function searchOnMailOut(string $query) {
        return $this->searchOnMail($query, "$this->_columnInstitute = :extra", [':extra' =>  $this->_origin], [':extra' => \PDO::PARAM_STR]);
    }
    public function searchOnMailDeadline(string $query) {
        return $this->searchOnMail($query, "$this->_columnDate > NOW()");
    }

    public function updateAlreadyReadMail(array $listId, string $value = 'TRUE') {
        $condition = 'id IN (';
        $limit = count($listId) -1;

        foreach ($listId as $key => $value) {
            $suffix = $key === $limit ? ')' : ',';
            $condition .= $value . $suffix;
        }

        return $this->_update("$this->_columnIsAlreadyRead = $value", $condition, [], []);
    }

    private function _get(string $columns, string $condition, array $value, bool $all = false, $type = []): ?array {
        if ($this->_connection) {
            try {

                $sql = "SELECT $columns FROM $this->_tablename WHERE $condition";

                $statement = $this->_connection->prepare($sql);

                $type = array_merge($type, [
                    \PDO::PARAM_STR,
                    \PDO::PARAM_STR,
                    \PDO::PARAM_STR,
                    \PDO::PARAM_STR,
                    \PDO::PARAM_STR
                ]);

                foreach ($value as $key => $value) {
                    $tmpType = $type[$key];
                    if (is_int($key)) {
                        ++$key;
                    }
                    $statement->bindValue($key, $value, $tmpType);
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
            logToScript('database connection closed');

            return null;
        }
    }
    private function _update(string $column, string $condition, array $value, array $type): bool {
        if ($this->_connection) {
            try {
                $sql = "UPDATE $this->_tablename SET $column WHERE $condition";

                $statement = $this->_connection->prepare($sql);

                foreach ($value as $key => $value) {
                    $tmpType = $type[$key];

                    if (is_int($key)) {
                        ++$key;
                    }
                    $statement->bindValue($key, $value, $tmpType);
                }
                
                $this->_connection->beginTransaction();

                $statement->execute();

                return $this->_connection->commit();
            } catch (\Throwable $th) {
                $this->_connection->rollBack();

                logToScript($th);

                return false;
            }
        } else {
            logToScript('database connection closed');

            return null;
        }
    }
}
