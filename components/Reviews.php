<?php namespace VojtaSvoboda\Reviews\Components;

use App;
use Cms\Classes\ComponentBase;
use VojtaSvoboda\Reviews\Facades\ReviewsFacade;
use VojtaSvoboda\Reviews\Models\Category;

class Reviews extends ComponentBase
{
    private $reviews;

    private $avgRating;

    public $category;

    public function componentDetails()
    {
        return [
            'name' => 'Reviews',
            'description' => 'Show reviews on your page.'
        ];
    }

    public function defineProperties()
    {
        return [
            'categoryFilter' => [
                'title' => 'Category identifier',
                'description' => 'Show only reviews from some category by slug',
                'type' => 'string',
                'default' => '{{ :category }}',
            ],
            'page' => [
                'title' => 'Page',
                'description' => '',
                'type' => 'string',
                'default' => '{{ :page }}'
            ],
            'perPage' => [
                'title' => 'Count per page',
                'description' => '',
                'type' => 'number',
                'default' => 10
            ],
        ];
    }

    public function onRun()
    {
        // category filter
        $category = null;
        if ($categorySlug = $this->property('categoryFilter')) {
            $category = $this->getCategory($categorySlug);
        }

        $page = $this->property('page', null);
        $page = $page === null ? null : $page;

        $perPage = $this->property('perPage', 10);

        $this->page['category'] = $category;
        $this->page['reviews'] = $this->reviews($category, $page, $perPage);
        $this->page['rating'] = $this->getAvgRating($category);
    }

    /**
     * Get reviews.
     *
     * @param Category $category Filter by category.
     * @param ?int $page
     * @param ?int $perPage
     *
     * @return mixed
     */
    public function reviews($category = null, $page = null, $perPage = null)
    {
        if ($this->reviews === null) {
            $this->reviews = $this->getFacade()->getApprovedReviews($category, $page, $perPage);
        }

        return $this->reviews;
    }

    private function getAvgRating($category = null)
    {
        if ($this->avgRating === null) {
            $this->avgRating = $this->getFacade()->getAvgRating($category);
        }

        return $this->avgRating;
    }

    /**
     * Get category by slug.
     *
     * @param $category
     *
     * @return mixed
     */
    public function getCategory($category)
    {
        if ($this->category === null) {
            $this->category = Category::where('slug', $category)->first();
        }

        return $this->category;
    }

    /**
     * Get Facade.
     *
     * @return ReviewsFacade
     */
    private function getFacade()
    {
        return App::make('vojtasvoboda.reviews.facade');
    }

    public function byCategory($category = null)
    {

        $categorySlug = $this->property('categoryFilter');
        $category = $this->getCategory($categorySlug);
        if(!$category)
        {
            return null;
        }
        $reviews = ReviewModel::isApproved()->orderBy('created_at','DESC');

        $reviews->whereHas('categories', function($query) use ($category) {
            $query->where('category_id', $category->id);
        });


        return $reviews->get();
    }

}
