<?php

namespace DB;

use Cassandra\Function_;
use PDO;
use PDOException;
use RuntimeException;

class PostgresDB
{
    private static $connect = null;

    private static function close(){
        self::$connect = null;
    }

     private static function connection(): PDO{
        if (self::$connect != null)
            return self::$connect;
        $dbInfo = parse_ini_file('config');
        if (!$dbInfo)
            throw new RuntimeException('Error load config from \'config\'');
        $dsn = sprintf('pgsql:host=%s;port=%d;dbname=%s', $dbInfo['PG_HOST'], $dbInfo['PG_PORT'], $dbInfo['PG_DATABASE']);
        try {
            self::$connect = new PDO($dsn, $dbInfo['PG_USERNAME'], $dbInfo['PG_PASSWORD']);
            self::$connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            self::$connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        }
        catch (PDOException $exception){
            self::close();
            die('Error connect to database: ' . $exception->getMessage());
        }
        return self::$connect;
    }

    static public function execute($sql, $params = [])
    {
        try {
            $stmt = self::connection()->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            die("Error execute: " . $e->getMessage());
        } finally {
            self::close();
        }
    }

    public static function query($sql, $params = [])
    {
        try {
            $stmt = self::connection()->prepare($sql);
            $stmt->execute($params);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error execute query: " . $e->getMessage());
        } finally {
            self::close();
        }
    }
}