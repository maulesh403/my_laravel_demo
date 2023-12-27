<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            // old syntex
            // $table->unsignedBigInteger('user_id')->after('id')->nullable();
            //$table->foreign('user_id')->references('id')->on('users')->onUpdate('cascade')->onDelete('cascade'); // must table datatype same as parent

            // new
            // Schema automatically asum that parent table is users from the column name, if not then name enter in the constrained function in single cot.
            // Also restrict and null on update and delete method available
            $table->foreignId('user_id')->after('id')->nullable()->constrained()->cascadeOnUpdate()->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tasks', function (Blueprint $table) {
            $table->dropForeign(['user_id']); // name of foreign key
            $table->dropColumn('user_id');
        });
    }
};
