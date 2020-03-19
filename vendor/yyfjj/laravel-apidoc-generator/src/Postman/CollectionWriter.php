<?php

namespace Mpociot\ApiDoc\Postman;

use Ramsey\Uuid\Uuid;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\URL;

class CollectionWriter
{
    /**
     * @var Collection
     */
    private $routeGroups;

    /**
     * @var string
     */
    private $baseUrl;

    /**
     * CollectionWriter constructor.
     *
     * @param Collection $routeGroups
     */
    public function __construct(Collection $routeGroups, $baseUrl)
    {
        $this->routeGroups = $routeGroups;
        $this->baseUrl = $baseUrl;
    }

    public function getCollection()
    {
        try {
            URL::forceRootUrl($this->baseUrl);
        } catch (\Error $e) {
            echo "Warning: Couldn't force base url as your version of Lumen doesn't have the forceRootUrl method.\n";
            echo "You should probably double check URLs in your generated Postman collection.\n";
        }

        $collection = [
            'variables' => [],
            'info' => [
                'name' => config('apidoc.postman.name') ?: config('app.name').' API',
                '_postman_id' => Uuid::uuid4()->toString(),
                'description' => config('apidoc.postman.description') ?: '',
                'schema' => 'https://schema.getpostman.com/json/collection/v2.0.0/collection.json',
            ],
            'item' => $this->routeGroups->map(function ($routes, $groupName) {
                return [
                    'name' => $groupName,
                    'description' => '',
                    'item' => $routes->map(function ($route) {
                        if(empty($route['jsonParameters'])){
                            $mode = $route['methods'][0] === 'PUT' ? 'urlencoded' : 'formdata';
                            $header = [];
                            $body = [
                                'mode' => $mode,
                                $mode => collect($route['bodyParameters'])->map(function ($parameter, $key) {
                                    return [
                                        'key' => $key,
                                        'value' => isset($parameter['value']) ? $parameter['value'] : '',
                                        'type' => 'text',
                                        'enabled' => true,
                                        'description'=>$parameter['description']
                                    ];
                                })->values()->toArray()
                            ];

                        }else{
                            $mode = 'raw';
                            $header = [['key'=>'Content-Type','value'=>'application/json',"description"=>'']];
                            $toArray = collect($route['jsonParameters'])->map(function ($parameter, $key) {
                                return isset($parameter['value']) ? $parameter['value'] : '';
                            })->toArray();
                            $body = [
                                'mode' => $mode,
                                $mode => json_encode([$toArray],JSON_UNESCAPED_UNICODE+JSON_PRETTY_PRINT )
                            ];
                        }
                        return $return = [
                            'name' => $route['title'] != '' ? $route['title'] : url($route['uri']),
                            'request' => [
                                'url' => in_array($route['methods'][0],['GET','PUT']) ? url($route['uri'])."?{{token}}".
                                    collect($route['queryParameters_1'])->map(function ($item,$key){
                                        return $key."=".$item['value'];
//                                        return [$key=>$item['value']];
                                    })->join("&")
                                     : url($route['uri']),
                                'method' => $route['methods'][0],
                                'header' => $header,
                                'body' => $body,
                                'description' => $route['description'],
                                'response' => [],
                            ],
                        ];
                    })->toArray(),
                ];
            })->values()->toArray(),
        ];

        return json_encode($collection);
    }
}
