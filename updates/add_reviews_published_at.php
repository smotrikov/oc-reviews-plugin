<?php namespace VojtaSvoboda\Reviews\Updates;

use October\Rain\Database\Schema\Blueprint;
use October\Rain\Database\Updates\Migration;
use Schema;


class AddReviewsPublishedAt extends Migration
{
    public function up()
    {
        Schema::table('vojtasvoboda_reviews_reviews', static function (Blueprint $table) {
            $table->dateTime('published_at')->nullable()->after('approved');
        });
    }

    public function down()
    {
        Schema::table('vojtasvoboda_reviews_reviews', static function (Blueprint $table) {
            $table->dropColumn('published_at');
        });
    }
}
