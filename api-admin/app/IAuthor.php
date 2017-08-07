<?php

namespace App;

interface IAuthor extends IPerson
{
    /**
     * @return boolean
     */
    public function isMember();

    /**
     * @return IUser | null
     */
    public function getUser();
}