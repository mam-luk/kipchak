<?php

namespace Mamluk\Kipchak\Components\Http;
use Exception;

/**
 * Class Codes
 * @package Mamluk\KipchakHelper\HttpCodes
 */

class Codes
{
    /**
     *
     */
    public static function getCode($code)
    {
        $codes = self::getCodes();

        if (isset($codes[$code])) {
            return $codes[$code];
        }

        throw new Exception('Incorrect Http Error code specified.');
    }

    /**
     *
     */
    public static function getCodes()
    {
        return [
            100 => 'CONTINUE',
            101 => 'SWITCHING PROTOCOLS',
            200 => 'OK',
            201 => 'CREATED',
            202 => 'ACCEPTED',
            203 => 'NON-AUTHORITATIVE INFORMATION',
            204 => 'NO_CONTENT',
            205 => 'RESET_CONTENT',
            206 => 'PARTIAL_CONTENT',
            300 => 'MULTIPLE_CHOICES',
            301 => 'MOVED_PERMANENTLY',
            302 => 'MOVED TEMPORARILY',
            303 => 'SEE OTHER',
            304 => 'NOT MODIFIED',
            305 => 'USE PROXY',
            306 => 'UNUSED',
            307 => 'TEMPORARY REDIRECT',
            400 => 'BAD REQUEST',
            401 => 'UNAUTHORIZED',
            402 => 'PAYMENT REQUIRED',
            403 => 'FORBIDDEN',
            404 => 'NOT FOUND',
            405 => 'METHOD NOT ALLOWED',
            406 => 'NOT ACCEPTABLE',
            407 => 'PROXY AUTHENTICATION REQUIRED',
            408 => 'REQUEST TIMEOUT',
            409 => 'CONFLICT',
            410 => 'GONE',
            411 => 'LENGTH REQUIRED',
            412 => 'PRECONDITION FAILED',
            413 => 'REQUEST ENTITY TOO LARGE',
            414 => 'REQUEST URI TOO LONG',
            415 => 'UNSUPPORTED MEDIA TYPE',
            416 => 'REQUEST RANGE NOT SATISFIABLE',
            417 => 'EXPECTION FAILED',
            500 => 'INTERNAL SERVER ERROR',
            501 => 'NOT IMPLEMENTED',
            502 => 'BAD GATEWAY',
            503 => 'SERVICE UNAVAILABLE',
            504 => 'GATEWAY TIMEOUT',
            505 => 'VERSION NOT SUPPORTED'
        ];
    }

}