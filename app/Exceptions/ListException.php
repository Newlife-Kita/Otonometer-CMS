<?php

namespace App\Exceptions;

use Exception;

class ListException extends Exception
{
    private $list;

    public function __construct(string $message, array $array, int $code = 0, Exception $previous = null)
    {
        parent::__construct($message, $code, $previous);
        $this->list = $array;
    }

    public function getList(){
        return $this->list;
    }
}
