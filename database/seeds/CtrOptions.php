<?php

use Illuminate\Database\Seeder;

class CtrOptions extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('ctr_dropdown_options')->insert([
        	[
        		'category'=>'gender',
        		'item'=>'Male',
                        'value'=>'MALE'
        		],
        	[
        		'category'=>'gender',
        		'item'=>'Female',
                        'value'=>'FEMALE'
        		],
        	[
        		'category'=>'civil_stat',
        		'item'=>'Single',
                        'value'=>'Single'
        		],
        	[
        		'category'=>'civil_stat',
        		'item'=>'Married',
                        'value'=>'Married'
        		],
        	[
        		'category'=>'civil_stat',
        		'item'=>'Divorced',
                        'value'=>'Divorced'
        		],
        	[
        		'category'=>'civil_stat',
        		'item'=>'Deceased',
                        'value'=>'Deceased'
        		],
        	[
        		'category'=>'civil_stat',
        		'item'=>'Widowed',
                        'value'=>'Widowed'
        		]
            
        	]);
    }
}
