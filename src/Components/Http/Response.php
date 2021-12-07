<?php
namespace x7x\Components\Http;

class Response
{
    public static function build($data, $code, $status)
    {
        return
            [
                'code' => $code,
                'status' => $status,
                'data' => $data
            ];
    }
}