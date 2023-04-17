<?php

class class_dbhelper {
    function getSingleRow($query) {
        global $db;
        if (!$result = $db->query($query))
            return -2;

        if (!$db->affected_rows)
            return -1;

        return mysqli_fetch_assoc($result);
    }

    function getResult ($query) {
        global $db;

        if (!$result = $db->query($query))
            return false;

        if (!$db->affected_rows)
            return -1;

        return $result;
    }
}