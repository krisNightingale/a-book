<?php

namespace App;

interface IBookHolder
{
    /**
     * @return IBook[]
     */
    public function getBooks();
}