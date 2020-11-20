<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Illuminate\Support\Facades\URL;

class TaskController extends Controller
{
    public function process_task_one(Request $request){
        $number_input = $request->number_input;
        $number_input_arr = str_split($number_input);

        $count_input = strlen($number_input);
        $count_swap = 0;
        $statement = "";

        for($i=0; $i<$count_input;$i++){  
            $index_now = $i;
            $index_next = $index_now+1;
            if($index_next<$count_input){
                $value_now = $number_input_arr[$index_now];
                $value_next = $number_input_arr[$index_next];

                if($value_now > $value_next){
                    $replacements = array($index_now=>$value_next,$index_next=>$value_now);

                    $replace = array_replace($number_input_arr,$replacements);
                    $implode_array = implode("",$replace);
                    $number_input = $implode_array;
                    $number_input_arr = str_split($number_input);

                    $count_swap++;
                    $statement_two = $count_swap.". [".$value_next.",".$value_now."] -> ".$implode_array;
                    if(empty($statement)){
                        $statement = $statement_two;
                    }else{
                        $statement = $statement."\n".$statement_two;
                    }

                    for($j=$i; $j>0;$j--){  
                        $index_now = $j;
                        $index_previous = $index_now-1;
                        if($index_previous>0||$index_previous==0){
                            $value_now  =  $number_input_arr[$index_now];
                            $value_previous =  $number_input_arr[$index_previous];
                            if($value_previous > $value_now){
                                $replacements = array($index_previous=>$value_now,$index_now=>$value_previous);

                                $replace = array_replace($number_input_arr,$replacements);
                                $implode_array = implode("",$replace);
                                $number_input = $implode_array;
                                $number_input_arr = str_split($number_input);
                                $count_swap++;
                                $statement_two = $count_swap.". [".$value_now.",".$value_previous."] -> ".$implode_array;
                      
                                if(empty($statement)){
                                    $statement = $statement_two;
                                }else{
                                    $statement = $statement."\n".$statement_two;
                                }
                            }
                        }
                    }
                }else{
                    //nothing to do
                }
            }
            
           
            
        }
        $statement = $statement."\n\n Jumlah swap: ".$count_swap;
        return $statement;
    }

    public function process_task_two(Request $request){
        $headers = $request->header('X-RANDOM');
        $counter = $request->counter;

        $log = ['counter' => $counter,
                'X-RANDOM' => $headers];
        
        $base_url = URL::to('/');

        $orderLog = new Logger('Success: POST '.$base_url);
        $orderLog->pushHandler(new StreamHandler(storage_path('logs/order.log')), Logger::INFO);
        $orderLog->info('Log', $log);

        $response = response()
            ->json([
                'status' => 'Success Post',
                'X-RANDOM' => $headers,
                'counter' => $counter
            ], 201);
        return $response;
    }
}
