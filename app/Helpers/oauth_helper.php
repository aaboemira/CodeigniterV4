<?php

/**
 * Generates a secure random authorization code.
 *
 * @return string
 */
function generateAuthorizationCode() {
    return bin2hex(random_bytes(16)); // 32 characters
}

/**
 * Generates a secure random access token.
 *
 * @return string
 */
function generateAccessToken() {
    return bin2hex(random_bytes(32)); // 64 characters
}

/**
 * Generates a secure random refresh token.
 *
 * @return string
 */
function generateRefreshToken() {
    return bin2hex(random_bytes(32)); // 64 characters
}
