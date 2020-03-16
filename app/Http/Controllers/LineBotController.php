<?php

namespace App\Http\Controllers;

use App\Services\Connpass;
use App\Services\MeetupBubbleBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use LINE\LINEBot;
use LINE\LINEBot\Event\MessageEvent\TextMessage;
use LINE\LINEBot\HTTPClient\CurlHTTPClient;
use LINE\LINEBot\MessageBuilder\FlexMessageBuilder;
use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder\CarouselContainerBuilder;


class LineBotController extends Controller
{
    public function index()
    {
        return view('linebot.index');
    }

    // public function parrot(Request $request)
    public function meetups(Request $request)
    {
        //Log function
        Log::debug($request->header());
        Log::debug($request->input());

        //LINEBot class
        $httpClient = new CurlHTTPClient(env('LINE_ACCESS_TOKEN'));
        $lineBot = new LINEBot($httpClient, ['channelSecret' => env('LINE_CHANNEL_SECRET')]);

        //validate signature (Array is error)
        $signature = $request->header('x-line-signature');
        if (!$lineBot->validateSignature($request->getContent(), $signature)) {
            abort(400, 'Invalid signature _ LineBotController.php');
        }

        //Extract event information from parseEventRequest
        $events = $lineBot->parseEventRequest($request->getContent(), $signature);

        Log::debug($events);

        //Reply to LINE channel
        foreach ($events as $event) {
            if (!($event instanceof TextMessage)) {
                Log::debug('Non text message has come');
                continue;
            }

            $connpass = new Connpass();
            $connpassResponse = $connpass -> searchMeetups($event->getText());

            //API results_returned
            
            if (empty($connpassResponse['results_returned'])){     
                $replyText = '検索結果 0件';      
                $replyToken = $event->getReplyToken();     
                $lineBot->replyText($replyToken, $replyText);
                continue;     
            }
            
            /*
            $replyText = ' ';
            $i=0;
            foreach($connpassResponse['events'] as $meetup) {
                
                $i++;
                $replyText .=
                    $i . ". " .
                    $meetup['title'] . "\n" .
                    $meetup['catch'] . "\n" .
                    "場所: " .
                    $meetup['address'] . "\n" .
                    $meetup['place'] . "\n" .
                    "参加数: " .
                    $meetup['accepted'] . " / " . 
                    "定員数: " .
                    $meetup['limit'] . "\n" .
                    "URL: " .
                    $meetup['event_url'] . "\n" .
                    "\n";
            }

            
            $replyToken = $event->getReplyToken();
            $lineBot->replyText($replyToken, $replyText);
            */
            
            
            $bubbles = [];
            foreach ($connpassResponse['events'] as $meetup) {
            $bubble = MeetupBubbleBuilder::builder(); 
            $bubble->setContents($meetup);
            $bubbles[] = $bubble;
            }

            $carousel = CarouselContainerBuilder::builder();
            $carousel->setContents($bubbles);

            $flex = FlexMessageBuilder::builder();
            $flex->setAltText('Searching');
            $flex->setContents($carousel);

            $lineBot->replyMessage($event->getReplyToken(), $flex);
            
        }        
    }
}
