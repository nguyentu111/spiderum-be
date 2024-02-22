<?php

namespace App\Supports;

class ArrayToTree {

    private array $list;

    public function __construct(array $list)
    {
        $this->list = $list;
    }

    public function buildTree($parentId = 0)
    {
        $branch = [];

        foreach ($this->list as $element) {
            if ($element['parent_id'] == $parentId) {
                $children = $this->buildTree($element['id']);
                if ($children) {
                    $element['children'] = $children;
                }
                $branch[$element['id']] = $element;
                unset($this->list[$element['id']]);
            }
        }
        return $branch;
    }
}
