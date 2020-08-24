<?php
<<<<<<< HEAD
namespace App\Exceptions;

class ApiException extends \Exception
{
    public function __construct($message,$code = 100){
        parent::__construct($message,$code);
=======

namespace App\Exceptions;



class ApiException extends \Exception
{
    public function __construct($message, $code = 100)
    {
        parent::__construct($message, $code);
>>>>>>> master
    }
}