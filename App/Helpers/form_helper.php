<?php

function get_form_field_value($userData, $sessionKey, $field) {
    if (session()->has($sessionKey) && isset(session($sessionKey)[$field])) {
        return session($sessionKey)[$field];
    }elseif (isset($userData) && isset($userData->$field)) {
        return $userData->$field;
    }
    return '';
}
