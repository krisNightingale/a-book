<?php

namespace App;

interface IPrimaryKey
{
    /**
     * @return int
     */
    public function getId();
}