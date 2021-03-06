<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class SlackController extends Controller
{
    
    public function showView(Request $request){
        $bot_token = env('SLACK_BOT_TOKEN');
        $url = 'https://slack.com/api/views.open';
        $view = $this->getView();
        $trigger_id = $request->input('trigger_id');

        $header = [
            'Content-type' => 'application/json',
            'Authorization' => "Bearer ".$bot_token
        ];

        $params = [
            // 'token' => $bot_token,
            'view' =>  json_encode($view),
            'trigger_id' => $trigger_id
        ];

        $client = new Client();

        $response = $client->request(
            'POST',
            $url,
            ['headers' => $header],
            ['body' => $params]
        );


        $log = json_decode($response->getBody()->getContents(), true);
        Log::info(print_r($log, true));

        // return response()->json([
        //     'log' => $log,
        //     'res' => $response,
        //     'url' => $url,
        //     'header' => $header,
        //     'parmas' => $params
        // ],200);

        return response('',200);
    }

    // 引き継ぎテンプレートのテンプレートを作る
    public function getView(){
        return [
            "callback_id" => "yukyu",
            "title" => "休暇申請ダイアログ",
            "submit_label" => "送信",
            "state" => "none",
            "elements" => [
                [
                    "type" => "select",
                    "label" => "休暇種類",
                    "name" =>  "holiday_type",
                    "options" => [
                        [
                            "label" => "有給休暇",
                            "value" => "type-full"
                        ],
                        [
                            "label" => "午前休暇",
                            "value" => "type-am"
                        ],
                        [
                            "label" => "午後休暇",
                            "value" => "type-pm"
                        ]
                    ],
                ],
                [
                    "type" => "text",
                    "label" => "休暇予定日",
                    "name" => "date",
                    "placeholder" => "2020-01-01",
                ],
                [
                    "type" => "textarea",
                    "label" => "休暇理由",
                    "name" =>  "reason",
                    "hint" => "理由を記入してください",
                ]
            ],
        ];
    }

}
