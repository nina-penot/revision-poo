<?php

// namespace myclasses;

// use Exception;

abstract class MyFunctions
{
    function is_num_ok($num)
    {
        if ($num < 0 or !is_numeric($num)) {
            return false;
        } else {
            return true;
        }
    }

    function is_id_ok($num)
    {
        if ($num < 0 or gettype($num) != "integer") {
            return false;
        } else {
            return true;
        }
    }

    function is_date_ok($date)
    {
        if (gettype($date) == "object") {
            if (get_class($date) == "DateTime") {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    function wrong_id()
    {
        throw new Exception("Wrong ID number. Must be integer and not negative.");
    }

    function wrong_number()
    {
        throw new Exception("Wrong value. Must be numerical and not negative.");
    }

    function wrong_date()
    {
        throw new Exception("Date invalid. Please use a new DateTime object.");
    }
}
