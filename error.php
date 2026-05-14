<?php

function pdo_debug_sql($sql, $params, $pdo) {
    foreach ($params as $key => $value) {
        if (is_string($value)) {
            $value = $pdo->quote($value);
        } elseif ($value === null) {
            $value = 'NULL';
        }

        if (is_string($key)) {
            $sql = str_replace($key, $value, $sql);
        } else {
            $sql = preg_replace('/\?/', $value, $sql, 1);
        }
    }
    return $sql;
}

// echo pdo_debug_sql($sql, $params, $pdo);

?>