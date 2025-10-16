<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFeedbacksTable extends Migration
{
    public function up()
    {
        Schema::create('feedbacks', function (Blueprint $table) {
            $table->id();
            $table->morphs('feedbackable'); // Crea feedbackable_id y feedbackable_type
            $table->unsignedBigInteger('user_id'); // Usuario que crea el feedback
            $table->text('feedback');
            $table->timestamp('fecha_feedback');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('feedbacks');
    }
}