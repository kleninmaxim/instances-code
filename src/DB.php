<?php

namespace src;

use PDO;
use PDOException;

class DB
{
    private static PDO $connect;

    public static function connect()
    {
        $db = require_once dirname(__DIR__) . '/config/config_db.php';;

        try {
            $dbh = new PDO($db['dsn'], $db['user'], $db['pass'], [PDO::ATTR_PERSISTENT => true]);

            $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo '[' . date('Y-m-d H:i:s') . '] ' . $e->getMessage() . PHP_EOL;

            die();
        }

        self::$connect = $dbh;

        return $dbh;
    }

    public static function getAllRegions(): bool|array
    {
        $sth = self::$connect->prepare(
            "SELECT * FROM `regions` WHERE `opt_in_status` = 'opt-in-not-required' OR `opt_in_status` = 'opted-in'"
        );

        $sth->execute();

        return $sth->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function insertPing($exchange, $max = null, $min = null, $average = null): void
    {
        $sth = self::$connect->prepare(
            "SELECT * FROM `region_queue` WHERE `status` = 0"
        );

        $sth->execute();

        $region = $sth->fetch(PDO::FETCH_ASSOC);


        $sth = self::$connect->prepare("INSERT INTO `ping` 
                                        (`instance_id`,  
                                         `region`, 
                                         `subnet`, 
                                         `exchange`, 
                                         `max`, 
                                         `min`, 
                                         `average`) 
                                         VALUES 
                                        ('{$region['instance_id']}',  
                                         '{$region['region']}', 
                                         '{$region['subnet']}', 
                                         '{$exchange}', 
                                         '{$max}', 
                                         '{$min}', 
                                         '{$average}')
                                     ");

        $sth->execute();
    }

    public static function updateRegionQueue(): void
    {
        $sth = self::$connect->prepare(
            "UPDATE `region_queue` SET `status` = 1"
        );

        $sth->execute();
    }
}