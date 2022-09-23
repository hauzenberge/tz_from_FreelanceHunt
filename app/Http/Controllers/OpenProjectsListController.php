<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Projects;

class OpenProjectsListController extends Controller
{
    public function index()
    {
        $pie_data = Projects::where('budget_currency', 'UAH')
            ->get()
            ->map(function ($item) {
                return $item['budget_amount'];
            })
            ->unique();

        $pie_lables = $pie_data->map(function ($item) {
            return $item . ' UAH';
        });

        return view('index', [
            'projects' => Projects::where('budget_currency', 'UAH')->get(),
            'pie_data' => $pie_data->toArray(),
            'pie_lables' => $pie_lables->toArray(),
            'options' => '
                    <option selected>Choose...</option>
                    <option value="up_to_500_UAH">up to 500 UAH</option>
                    <option value="500_1000_UAH">500-1000 UAH</option>
                    <option value="1000_5000_UAH">1000-5000 UAH</option>
                    <option value="more_than_5000_UAH">more than 5000 UAH</option>
            '
        ]);
    }

    public function filter(Request $request)
    {
        // dd($request->input());
        switch ($request->input('filter')) {
            case 'up_to_500_UAH': {
                    $return = Projects::where('budget_currency', 'UAH')
                        ->where('budget_amount', '<', 500)
                        ->get();
                    $options =  '
                                <option value="up_to_500_UAH" selected>up to 500 UAH</option>
                                <option value="500_1000_UAH">500-1000 UAH</option>
                                <option value="1000_5000_UAH">1000-5000 UAH</option>
                                <option value="more_than_5000_UAH">more than 5000 UAH</option>
                        ';
                    break;
                }

            case '500_1000_UAH': {
                    $return = Projects::where('budget_currency', 'UAH')
                        ->where('budget_amount', '>', 500)
                        ->where('budget_amount', '<', 1000)
                        ->get();
                    $options =  '
                                    <option value="up_to_500_UAH">up to 500 UAH</option>
                                    <option value="500_1000_UAH" selected>500-1000 UAH</option>
                                    <option value="1000_5000_UAH">1000-5000 UAH</option>
                                    <option value="more_than_5000_UAH">more than 5000 UAH</option>
                            ';
                    break;
                }
            case '1000_5000_UAH': {
                    $return = Projects::where('budget_currency', 'UAH')
                        ->where('budget_amount', '>', 1000)
                        ->where('budget_amount', '<', 5000)
                        ->get();
                    $options =  '
                                <option value="up_to_500_UAH">up to 500 UAH</option>
                                <option value="500_1000_UAH">500-1000 UAH</option>
                                <option value="1000_5000_UAH" selected>1000-5000 UAH</option>
                                <option value="more_than_5000_UAH">more than 5000 UAH</option>
                        ';
                    break;
                }
            case 'more_than_5000_UAH': {
                    $return = Projects::where('budget_currency', 'UAH')
                        ->where('budget_amount', '>', 5000)
                        ->get();
                    $options =  '
                            <option value="up_to_500_UAH">up to 500 UAH</option>
                            <option value="500_1000_UAH">500-1000 UAH</option>
                            <option value="1000_5000_UAH">1000-5000 UAH</option>
                            <option value="more_than_5000_UAH" selected>more than 5000 UAH</option>
                        ';
                    break;
                }
            default: {
                    $return = Projects::where('budget_currency', 'UAH')->get();
                    $options =  '
                            <option selected>Choose...</option>
                            <option value="up_to_500_UAH">up to 500 UAH</option>
                            <option value="500_1000_UAH">500-1000 UAH</option>
                            <option value="1000_5000_UAH">1000-5000 UAH</option>
                            <option value="more_than_5000_UAH">more than 5000 UAH</option>
                    ';
                    break;
                }
        }
        
        $pie_data = Projects::where('budget_currency', 'UAH')
            ->get()
            ->map(function ($item) {
                return $item['budget_amount'];
            })
            ->unique();

        $pie_lables = $pie_data->map(function ($item) {
            return $item . ' UAH';
        });
        
        return view('index', [
            'projects' => $return,
            'options' => $options,
            'pie_data' => $pie_data->toArray(),
            'pie_lables' => $pie_lables->toArray(),

        ]);
    }
}
