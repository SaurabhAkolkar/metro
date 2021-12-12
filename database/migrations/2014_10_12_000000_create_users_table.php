<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->string('gender', 1);
            $table->string('profile_for', 80);
            $table->string('password');
            $table->rememberToken();
            $table->string('verify_token')->nullable();
            $table->string('avatar_medium',255)->nullable();
            $table->string('avatar_thumb',255)->nullable();
            $table->string('avatar_big',255)->nullable();
            $table->string('area_of_interest', 300)->nullable();
            $table->tinyInteger('blood_group');
            $table->string('complexion')->nullable();
            $table->string('body_type')->nullable();
            $table->string('weight')->nullable();
            $table->string('about_me', 1500)->nullable();
            $table->tinyInteger('height');
            $table->string('paternal_tribe')->nullable();
            $table->string('maternal_tribe')->nullable();
            $table->string('qualification', 100);
            $table->mediumInteger('occupation');
            $table->tinyInteger('income');
            $table->string('work_place');
            $table->string('tehsil')->nullable();
            $table->string('organisation_name');
            $table->string('city');
            $table->integer('state',8);
            $table->integer('district',8);
            $table->index('state');
            $table->index('district');
            $table->string('siblings')->nullable();
            $table->string('bro_and_sister_in_law')->nullable();
            $table->string('sis_and_brother_in_law')->nullable();
            $table->string('father_email')->nullable();
            $table->string('mobile')->unique();
            $table->string('current_address', 500)->nullable();
            $table->integer('pin');
            $table->string('whatsapp_no', 13)->nullable();
            $table->string('challenged')->nullable();
            $table->string('permanent_address', 500)->nullable();
               
            // CriticalFields
            $table->tinyInteger('marital_status');
            $table->string('aadhaar', 50);
            $table->date('dob');
            $table->string('birth_place');
            $table->string('birth_time');
            $table->tinyInteger('manglik');
            $table->string('gotra');
            $table->string('gotra_nanihal');


            //family
            $table->string('family_address', 500)->nullable();
            $table->string('father_name')->nullable();
            $table->string('father_occupation')->nullable();
            $table->string('family_status')->nullable();
            $table->string('family_district')->nullable();
            $table->string('mother_name')->nullable();
            $table->string('grand_father_name')->nullable();
            $table->string('family_state')->nullable();
            $table->string('family_pin')->nullable();
            $table->string('mother_occupation')->nullable();
            $table->string('family_type')->nullable();
            $table->string('family_income')->nullable();
            $table->string('family_city')->nullable();
            $table->string('maternal_grand_father_name_address')->nullable();
            $table->string('family_value')->nullable();
            $table->string('mobile2', 15)->nullable();
            $table->string('brother')->nullable();
            $table->string('sister')->nullable();
            
            //lifestyle
            $table->string('drinking')->nullable();
            $table->string('dietary')->nullable();
            $table->string('hobbies', 500)->nullable();
            $table->string('smoking')->nullable();
            $table->string('language')->nullable();
            $table->string('interests', 500)->nullable();


           
            $table->tinyInteger('shortlisted')->default(0);
            $table->tinyInteger('is_active')->default(0);
            $table->tinyInteger('critical')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
