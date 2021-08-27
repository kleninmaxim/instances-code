<?php

namespace src;

class DB
{
    private static $connect;

    public static function connect()
    {

        //подключение к БД
        $db = require_once dirname(__DIR__) . '/config/config_db.php';;

        try {

            $dbh = new \PDO($db['dsn'], $db['user'], $db['pass'], [
                \PDO::ATTR_PERSISTENT => true
            ]);

            $dbh->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

        } catch (PDOException $e) {

            print "Error!: " . $e->getMessage() . "<br/>";

            die();

        }

        self::$connect = $dbh;

        return $dbh;

    }

    public static function getAllRegions()
    {

        $sth = self::$connect->prepare(
            "SELECT * FROM `regions` WHERE `opt_in_status` = 'opt-in-not-required' OR `opt_in_status` = 'opted-in'"
        );

        $sth->execute();

        return $sth->fetchAll(\PDO::FETCH_ASSOC);

    }
}