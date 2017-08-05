<?php

namespace App;

interface IPerson extends IBookHolder, IPrimaryKey
{
    public function getFirstName();

    public function getLastName();
}