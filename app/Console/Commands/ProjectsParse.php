<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

use  App\Helpers\APIHelper;
use App\Models\Categories;
use App\Models\Projects;
use App\Models\Currencies;

class ProjectsParse extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'projects:parse';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }



    public static function ProjectsParce()
    {
    }

    public function ProjectParce()
    {
        $helper = new APIHelper;
        $list = $helper->ProjectsParce(1);
        $pages_end = intval(substr($list['links']['last'], 55));
        $j = 1;
        for ($i = 2; $i <= $pages_end; $i++) {
            $merge = $helper->ProjectsParce($i);
            if (array_key_exists('data', $merge)) {
                $this->info($j . ') Add data, count ' . count($merge['data']));
                $j++;
                $list['data'] = array_merge($list['data'],  $merge['data']);
            }
        }
        $this->info('Map Data');
        return collect($list['data'])->map(function ($item) {
            if (($item["attributes"]['budget'] != null)
                and ($item["attributes"]["employer"] != null)
            ) {
                $tags = collect($item["attributes"]["tags"])->map(function ($item) {
                    return $item['name'];
                });
               // dd($tags->toJson());
                return [
                    'project_name' => $item["attributes"]["name"],
                    'project_link' => $item["links"]["self"]["web"],
                    'budget_amount' => $item["attributes"]['budget']['amount'],
                    'categories' => $tags->toJson(),
                    'budget_currency' => $item["attributes"]['budget']['currency'],
                    'employer' => $item["attributes"]["employer"]["first_name"] . ' ' . $item["attributes"]["employer"]["login"]
                ];
            }
        })
            ->filter(function ($item) {
                if ($item != null) {
                    return $item;
                }
            });
    }


    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $this->info("Start parsing projects data");
        $insert_data = $this->ProjectParce();
        $this->info('Insert Projects');
        Projects::upsert($insert_data->toArray(), ['project_name', 'project_link'], ['budget_amount',  'categories',  'budget_currency', 'employer']);

        $this->info("Select Categories");
        $categories = $insert_data->map(function ($item) {
            if (count(json_decode($item["categories"], true)) != 0) {
                return json_decode($item["categories"], true);
            }
        })
            ->filter(function ($item) {
                if ($item != null) {
                    return $item;
                }
            })
            ->unique()
            ->values();

        $insert_categoies = [];
        foreach ($categories as $category) {
            $insert_categoies = array_merge($insert_categoies, $category);
        }
        $insert_categoies = collect($insert_categoies)
            ->unique()
            ->map(function ($item) {
                return [
                    'name' => $item
                ];
            })
            ->values();
        $this->info('Insert Categories');
        Categories::upsert($insert_categoies->toArray(), [], ['name']);

        $this->info("Select Currencies");
        $insert_currencies = $insert_data->map(function ($item) {
            //dd($item['budget_currency']);
            return [
                'code' => $item['budget_currency']
            ];
        })
            ->unique()
            ->values();
      //  dd($insert_currencies);
        $this->info('Insert Currencies');
        Currencies::upsert($insert_currencies->toArray(), [], ['code']);

        return 0;
    }
}
