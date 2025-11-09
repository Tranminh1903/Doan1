<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class SeatSeeder extends Seeder
{
    public function run(): void
    {
        $now  = Carbon::now();
        $rows = [];

        foreach (range(1,10) as $col) {
            $rows[] = ['theaterID'=>1,'verticalRow'=>'A','horizontalRow'=>$col,'seatType'=>'vip','status'=>'available','created_at'=>$now,'updated_at'=>$now];
        }
        foreach (['B','C'] as $r) { 
            foreach (range(1,10) as $col) {
                $rows[] = ['theaterID'=>1,'verticalRow'=>$r,'horizontalRow'=>$col,'seatType'=>'normal','status'=>'available','created_at'=>$now,'updated_at'=>$now];
            }
        }
        foreach (range(1,10) as $col) { 
            $rows[] = ['theaterID'=>1,'verticalRow'=>'D','horizontalRow'=>$col,'seatType'=>'couple','status'=>'available','created_at'=>$now,'updated_at'=>$now];
        }

        foreach (range(1,10) as $col) {
            $rows[] = ['theaterID'=>2,'verticalRow'=>'A','horizontalRow'=>$col,'seatType'=>'vip','status'=>'available','created_at'=>$now,'updated_at'=>$now];
        }
        foreach (range(1,10) as $col) {
            $rows[] = ['theaterID'=>2,'verticalRow'=>'B','horizontalRow'=>$col,'seatType'=>'normal','status'=>'available','created_at'=>$now,'updated_at'=>$now];
        }

        DB::table('seats')->upsert(
            $rows,
            ['theaterID','verticalRow','horizontalRow'],
            ['seatType','status','updated_at']
        );
    }
}
