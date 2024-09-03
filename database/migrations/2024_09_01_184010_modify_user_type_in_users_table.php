<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ModifyUserTypeInUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            // Remove old user_type column if it exists
            if (Schema::hasColumn('users', 'user_type')) {
                $table->dropColumn('user_type');
            }

            // Add new user_type column with default value
            // $table->string('user_type')->default('developer');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('user_type', ['developer', 'manager', 'QA']);
        });
    }
}
