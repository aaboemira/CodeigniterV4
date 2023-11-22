<?php

function get_form_field_value($userData, $sessionKey, $field) {
    if (isset($userData) && isset($userData->$field)) {
        return $userData->$field;
    } elseif (session()->has($sessionKey) && isset(session($sessionKey)[$field])) {
        return session($sessionKey)[$field];
    }
    return '';
}
