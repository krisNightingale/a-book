<?php

namespace App;

interface IBook extends IPrimaryKey
{
    /**
     * @return string
     */
    public function getName();

    /**
     * @return string
     */
    public function getISBN();

    /**
     * @return IAuthor[]
     */
    public function getAuthors();

    /**
     * @return string
     */
    public function getDescription();

    /**
     * @return int
     */
    public function getYearOfPublishing();

    /**
     * @return int
     */
    public function getFormat();

    /**
     * @return int
     */
    public function getPagesCount();
}