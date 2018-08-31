<?php

namespace AdamJedlicka\Luna\Traits;

trait HasLinks
{
    /**
     * Array of link templates
     *
     * @var array
     */
    protected $links = [];

    /**
     * Sets the template for generation of detailLink
     *
     * @param string $detailLink
     * @return self
     */
    public function detailLink(string $detailLink) : self
    {
        $this->links['detail'] = $detailLink;

        return $this;
    }

    /**
     * Sets the template for generation of editLink
     *
     * @param string $editLink
     * @return self
     */
    public function editLink(string $editLink) : self
    {
        $this->links['edit'] = $editLink;

        return $this;
    }

    /**
     * Sets the template for generation of deleteLink
     *
     * @param string $deleteLink
     * @return self
     */
    public function deleteLink(string $deleteLink) : self
    {
        $this->links['delete'] = $deleteLink;

        return $this;
    }

    /**
     * Sets the template for generation of detachLink
     *
     * @param string $detachLink
     * @return self
     */
    public function detachLink(string $detachLink) : self
    {
        $this->links['detach'] = $detachLink;

        return $this;
    }
}
