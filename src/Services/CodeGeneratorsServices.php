<?php

namespace App\Services;

class CodeGeneratorsServices
{

    /**
     * @param string|null $prefix
     * @param int $length
     * @return string
     */
    public function generate(?string $prefix, int $length = 3): string {
        if ($prefix){
            return "$prefix".date('Y').date('s').$this->uuid($length);
        }
        return $this->uuid($length);
    }

    private function uuid($length){
        $chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        while (strlen($chars) < $length){
            $chars .= $chars;
        }
        return substr(str_shuffle($chars), 0, $length);
    }
}