<?php

namespace VojtaSvoboda\Reviews\Components;

use Cms\Classes\ComponentBase;
use VojtaSvoboda\Reviews\Models\Category as CategoryModel;

class Categories extends ComponentBase
{
    public $categories;

    public function componentDetails()
    {
        return [
            'name'        => 'Список категорий отзывов',
            'description' => 'Расширяет оригинальный компонент'
        ];
    }
    public function defineProperties()
    {
        return [];
    }

    public function onRun()
    {
        $this->categories = $this->page['categories'] = CategoryModel::isEnabled()->orderBy('sort_order')->get();
    }
}
