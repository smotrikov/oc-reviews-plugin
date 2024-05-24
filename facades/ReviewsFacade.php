<?php namespace VojtaSvoboda\Reviews\Facades;

use VojtaSvoboda\Reviews\Models\Category;
use VojtaSvoboda\Reviews\Models\Review;

/**
 * Main plugin facade. You should call this methods a priority.
 *
 * @package VojtaSvoboda\Reviews\Facades
 */
class ReviewsFacade
{
    /** @var Review $reviews */
    private $reviews;

    /**
     * ReviewsFacade constructor.
     *
     * @param Review $reviews
     */
    public function __construct(Review $reviews)
    {
        $this->reviews = $reviews;
    }

    /**
     * Create new review.
     *
     * @param array $data
     *
     * @return array
     */
    public function storeReview(array $data)
    {
        return $this->reviews->create($data);
    }

    /**
     * Get approved reviews (for displaying at frontend).
     *
     * @param Category $category Filter results by category.
     * @param ?int $page
     * @param ?int $perPage
     *
     * @return array
     */
    public function getApprovedReviews($category = null, $page = null, $perPage = null)
    {
        $query = $this->reviews->isApproved()->orderBy('published_at', 'DESC');

        if ($category !== null) {
            $query->whereHas('categories', function($query) use ($category) {
                $query->where('category_id', $category->id);
            });
        }

        return $page === null
            ? $query->get()
            : $query->paginate($perPage, $page);
    }

    /**
     * @param $category
     * @return float|int
     */
    public function getAvgRaiting($category = null)
    {
        $query = $this->reviews->isApproved();

        if ($category !== null) {
            $query->whereHas('categories', function($query) use ($category) {
                $query->where('category_id', $category->id);
            });
        }

        $avg = $query->avg('rating');

        return $avg === null ? 0 : round($avg, 2);
    }


    /**
     * Get non approved reviews (for admin approval).
     *
     * @return array
     */
    public function getNonApprovedReviews()
    {
        return $this->reviews->notApproved()->orderBy('sort_order')->get();
    }

    /**
     * Find one review.
     *
     * @param $value
     * @param string $key
     *
     * @return Review
     */
    public function findOne($value, $key = 'id')
    {
        return $this->reviews->where($key, $value)->first();
    }
}
