<?php

class Logger
{
    protected static $filePath = 'db.log';

    public static function log(string $message): void
    {
        $time = date('Y-m-d H:i:s');

        file_put_contents(self::$filePath, "[$time] $message" . PHP_EOL, FILE_APPEND);
    }
}