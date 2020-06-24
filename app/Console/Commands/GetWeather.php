<?php

namespace App\Console\Commands;

use App\Model\WeatherDetail;
use Illuminate\Console\Command;
use Goutte\Client as GoutteClient;
use App\Model\Weather;

class GetWeather extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'weather:get';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'get all places weather by curl';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $client = new GoutteClient();
        /** 原 https://www.cwb.gov.tw/V8/C/W/week.html 中產出表格來源透過dev tool 可以看到是經由XHR請求得來，故改抓這url */
        $url = 'https://www.cwb.gov.tw/V8/C/W/County/MOD/wf7dayNC_NCSEI/ALL_Week.html';
        $crawler = $client->request('GET', $url);

        $crawler->filter('.day')->each(function ($regionNode) {
            $regionText = $regionNode->filter('a > .heading_3')->text();
            $regionId = Weather::getRegionId($regionText);
            $i = 0;
            $regionNode->filter('td')->each(function ($eachDate) use ($regionId, &$i) {
                WeatherDetail::updateOrInsert(
                    [
                        'region_id' => $regionId,
                        'date' => date('Y/m/d', strtotime('+ ' . $i . ' days')),
                        'temperature' => $eachDate->filter('.tem-C')->text(),
                        'overview' => $eachDate->filter('img')->attr('title')
                    ]
                );
                ++$i;
            });
        });


        exit;

    }
}
