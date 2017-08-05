<?php

namespace App;

interface IUser extends IPerson
{
    public function getEmail();
}