<?php
/**
 * Created by PhpStorm.
 * User: mrcake
 * Date: 12/14/17
 * Time: 12:14 PM
 */

namespace app\utils;


class Paginator implements \Iterator
{
    protected $buttons = [];
    protected $currentPage = 1;

    protected $prevItem = [];
    protected $nextItem = [];

    public function __construct($baseUri, $totalCount, $currentPage)
    {
        $urlParamKey = 'page';
        $hasQueryParams = strpos($baseUri, '?') !== false;
        for ($i = 1; $i <= $totalCount; $i++) {
            $itemLink = $baseUri;
            $pageParams = http_build_query([$urlParamKey => $i]);
            $addSymbol = $hasQueryParams ? '&' : '?';
            $itemLink .= $addSymbol . $pageParams;
            $this->buttons[$i] = $itemLink;
        }

        $this->currentPage = $currentPage;
        $this->prevItem = $this->createPrevValue();
        $this->nextItem = $this->createNextValue();
    }

    public function __toString()
    {
        return __METHOD__;
    }

    /**
     * Return the current element
     * @link http://php.net/manual/en/iterator.current.php
     * @return mixed Can return any type.
     * @since 5.0.0
     */
    public function current()
    {
        return $this->buttons[$this->currentPage];
    }

    /**
     * Move forward to next element
     * @link http://php.net/manual/en/iterator.next.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function next()
    {
        $this->currentPage++;
    }

    protected function createNextValue()
    {
        $nextKey = $this->key() + 1;
        $nextValue = $this->buttons[$nextKey] ?? false;
        return [$nextKey, $nextValue];
    }

    public function getNextValue()
    {
        return $this->nextItem;
    }

    protected function createPrevValue()
    {
        $prevKey = $this->key() - 1;
        $prevValue = $this->buttons[$prevKey] ?? false;
        return [$prevKey, $prevValue];
    }

    public function getPrevValue()
    {
        return $this->prevItem;
    }

    /**
     * Return the key of the current element
     * @link http://php.net/manual/en/iterator.key.php
     * @return mixed scalar on success, or null on failure.
     * @since 5.0.0
     */
    public function key()
    {
        return $this->currentPage;
    }

    /**
     * Checks if current position is valid
     * @link http://php.net/manual/en/iterator.valid.php
     * @return boolean The return value will be casted to boolean and then evaluated.
     * Returns true on success or false on failure.
     * @since 5.0.0
     */
    public function valid()
    {
        return isset($this->buttons[$this->currentPage]);
    }

    /**
     * Rewind the Iterator to the first element
     * @link http://php.net/manual/en/iterator.rewind.php
     * @return void Any returned value is ignored.
     * @since 5.0.0
     */
    public function rewind()
    {
        $this->currentPage = 1;
    }
}