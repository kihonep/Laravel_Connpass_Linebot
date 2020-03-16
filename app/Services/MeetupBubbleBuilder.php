<?php

namespace App\Services;

use LINE\LINEBot\MessageBuilder\Flex\ContainerBuilder;
use Illuminate\Support\Arr;


class MeetupBubbleBuilder implements ContainerBuilder
{
    private const GOOGLE_MAP_URL = 'https://www.google.com/maps';
    private const TWITTER_SEARCH_URL = 'https://twitter.com/search?lang=ja';
    
    private $title;
    private $started_at;
    private $ended_at;
    private $address;
    private $accepted;
    private $limit;
    private $description;
    private $lat;
    private $lon;
    private $event_url;
    // private $hash_tag;
    private $catch;
    private $owner_display_name;
    
    
	public static function builder(): MeetupBubbleBuilder
    {
        return new self();
    }
    
    
	public function setContents(array $meetup): void
    {
        $this->title = Arr::get($meetup, 'title', null);
        $this->started_at = Arr::get($meetup, 'started_at', null);
        $this->ended_at = Arr::get($meetup, 'ended_at', null);
        $this->address = Arr::get($meetup, 'address', null);
        $this->accepted = Arr::get($meetup, 'accepted', null);
        $this->limit = Arr::get($meetup, 'limit', null);
        $this->description = Arr::get($meetup, 'description', null);
        $this->lat = Arr::get($meetup, 'lat', null);
        $this->lon = Arr::get($meetup, 'lon', null);
        $this->event_url = Arr::get($meetup, 'event_url', null);
        // $this->hash_tag = Arr::get($meetup, 'hash_tag', null);
        $this->catch = Arr::get($meetup, 'catch', null);
        $this->owner_display_name = Arr::get($meetup, 'owner_display_name', null);
        
    }
	public function build(): array
    {
        $array = [
            'type' => 'bubble',
            'size' => 'kilo',
            'body' => [
                'type' => 'box',
                'layout' => 'vertical',
                'contents' => [
                    /*
                    [
                        'type'=> 'box',
                        'layout'=> 'horizontal',
                        'contents'=> [
                            [
                                'type'=> 'text',
                                'text'=> ' ',
                                'size'=> 'xs',
                                'color'=> '#ffffff',
                                'align'=> 'center',
                                'gravity'=> 'center'
                            ]
                        ],
                        'backgroundColor'=> '#EC3D44',
                        'flex'=> 0,
                        'width'=> '40px',
                        'height'=> '25px',
                        'position'=> 'relative',
                        'offsetBottom'=> '5px',
                        'offsetStart'=> '10px',
                        'cornerRadius'=> '100px',
                        'width'=>'48px',
                        'height'=>'25px'
                    ],
                    */
                    [
                        'type' => 'text',
                        'text' => $this->title,
                        'wrap' => true,
                        'weight' => 'bold',
                        'size' => 'lg',
                    ],
                    
                    [
                        'type' => 'box',
                        'layout' => 'vertical',
                        'margin' => 'lg',
                        'spacing' => 'sm',
                        'contents' => [

                            [
                                'type' => 'box',
                                'layout' => 'baseline',
                                'spacing' => 'xs',
                                'contents' => [
                                    [
                                        'type' => 'text',
                                        'text' => '概要',
                                        'color' => '#aaaaaa',
                                        'size' => 'xs',
                                        'flex' => 4
                                    ],
                                    [
                                        'type' => 'text',
                                        'text' => $this->catch.' ',
                                        'wrap' => true,
                                        'maxLines'=> 3,
                                        'color' => '#666666',
                                        'size' => 'xs',
                                        'flex' => 12
                                        
                                    ]
                                ]
                            ],
                            
                            [
                                'type' => 'box',
                                'layout' => 'baseline',
                                'spacing' => 'xs',
                                'contents' => [
                                    [
                                        'type' => 'text',
                                        'text' => 'アクセス',
                                        'color' => '#aaaaaa',
                                        'size' => 'xs',
                                        'flex' => 4
                                    ],
                                    [
                                        'type' => 'text',
                                        'text' => $this->address,
                                        'wrap' => true,
                                        'color' => '#666666',
                                        'size' => 'xs',
                                        'flex' => 12
                                    ]
                                ]
                            ],

                            [
                                'type' => 'box',
                                'layout' => 'baseline',
                                'spacing' => 'xs',
                                'contents' => [
                                    [
                                        'type' => 'text',
                                        'text' => '参加数',
                                        'color' => '#aaaaaa',
                                        'size' => 'xs',
                                        'flex' => 4
                                    ],
                                    [
                                        'type' => 'text',
                                        'text' => $this->accepted . ' / ' . $this->limit ,
                                        'wrap' => true,
                                        'color' => '#666666',
                                        'size' => 'xs',
                                        'flex' => 12
                                    ]
                                ]
                            ],
                            
                             
                            [
                                'type' => 'box',
                                'layout' => 'baseline',
                                'spacing' => 'xs',
                                'contents' => [
                                    [
                                        'type' => 'text',
                                        'text' => '開催日時',
                                        'color' => '#aaaaaa',
                                        'size' => 'xs',
                                        'flex' => 4
                                    ],
                                    [
                                        'type' => 'text',
                                        'text' => date('n月j日 H:i', strtotime($this->started_at)).'～'.date('H:i', strtotime($this->ended_at)),
                                        'wrap' => true,
                                        'maxLines'=> 1,
                                        'color' => '#666666',
                                        'size' => 'xs',
                                        'flex' => 12
                                    ]
                                ]
                            ],

                            [
                                'type' => 'box',
                                'layout' => 'baseline',
                                'spacing' => 'xs',
                                'contents' => [
                                    [
                                        'type' => 'text',
                                        'text' => '管理者',
                                        'color' => '#aaaaaa',
                                        'size' => 'xs',
                                        'flex' => 4
                                    ],
                                    [
                                        'type' => 'text',
                                        'text' => $this->owner_display_name,
                                        'wrap' => true,
                                        'color' => '#666666',
                                        'size' => 'xs',
                                        'flex' => 12
                                    ]
                                ]
                            ],

                            
                        ]
                    ]
                ]
            ],
            
            'footer' => [
                'type' => 'box',
                'layout' => 'vertical',
                'spacing' => 'xs',
                'contents' => [
                    [
                        'type' => 'button',
                        'style' => 'link',
                        'height' => 'sm',
                        'action' => [
                            'type' => 'uri',
                            'label' => '地図を見る',
                            'uri' => self::GOOGLE_MAP_URL . '?q=' . $this->lat . ',' . $this->lon,
                        ]
                    ],
                    /*
                    [
                        'type' => 'button',
                        'style' => 'link',
                        'height' => 'sm',
                        'action' => [
                            'type' => 'uri',
                            'label' => 'ハッシュタグを見る',
                            'uri' => self::TWITTER_SEARCH_URL . '&q=%23' . $this->hash_tag,
                        ]
                    ],
                    */
                    [
                        'type' => 'button',
                        'style' => 'link',
                        'height' => 'sm',
                        'action' => [
                            'type' => 'uri',
                            'label' => '詳しく見る',
                            'uri' => $this->event_url,
                        ]
                    ],

                    
                    
                ],
                'flex' => 0
            ]
        
            
        ];

        return $array;
    }	
}

