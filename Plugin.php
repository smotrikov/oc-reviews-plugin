<?php namespace VojtaSvoboda\Reviews;

use Backend;
use System\Classes\PluginBase;
use System\Classes\PluginManager;
use VojtaSvoboda\Reviews\Components\Reviews;

class Plugin extends PluginBase
{
    public function boot()
    {
        if (PluginManager::instance()->hasPlugin('smotrikov.breadcrumbs')) {
            \Event::listen('smotrikov.breadcrumbs.title.map', function ($page, $currentPage) {
                if (!isset($currentPage->components['reviews'])) {
                    return null;
                }

                $component = $currentPage->components['reviews'];

                if ($page->baseFileName === 'reviews/category') {
                    /** @var Reviews $component */
                    return $component->category->name ?? null;
                }
            });
        }

        $this->app->bind('vojtasvoboda.reviews.facade', 'VojtaSvoboda\Reviews\Facades\ReviewsFacade');
    }

    public function registerNavigation()
    {
        return [
            'reviews' => [
                'label' => 'Reviews',
                'url' => Backend::url('vojtasvoboda/reviews/reviews'),
                'icon' => 'icon-star-half-o',
                'permissions' => ['vojtasvoboda.reviews.*'],
                'order' => 510,
                'sideMenu' => [
                    'reviews' => [
                        'label' => 'Reviews',
                        'url' => Backend::url('vojtasvoboda/reviews/reviews'),
                        'icon' => 'icon-star-half-o',
                        'permissions' => ['vojtasvoboda.reviews.reviews'],
                        'order' => 100,
                    ],
                    'categories' => [
                        'permissions' => ['vojtasvoboda.reviews.categories'],
                        'label' => 'Categories',
                        'icon' => 'icon-folder',
                        'url' => Backend::url('vojtasvoboda/reviews/categories'),
                        'order' => 200,
                    ],
                ],
            ],
        ];
    }

    public function registerComponents()
    {
        return [
            'VojtaSvoboda\Reviews\Components\Reviews' => 'reviews',
            'VojtaSvoboda\Reviews\Components\Categories' => 'reviewCategories',
        ];
    }

    public function registerFormWidgets()
    {
        return [
            'VojtaSvoboda\Reviews\FormWidgets\StarRating' => 'starrating',
        ];
    }
}
