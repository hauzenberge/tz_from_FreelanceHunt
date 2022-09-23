@extends('layout.app')

@section('content')

<canvas id="myChart" width="400" height="400"></canvas>

<div class="card">
    <div class="card-header">
        <form class="form-inline" action="{{url('/filter')}}" method="get">
            <label class="my-1 mr-2" for="inlineFormCustomSelectPref">Preference</label>
            <select class="custom-select my-1 mr-sm-2" name="filter" id="inlineFormCustomSelectPref">
                {{-- <option selected>Choose...</option>
                <option value="up_to_500_UAH">up to 500 UAH</option>
                <option value="500_1000_UAH">500-1000 UAH</option>
                <option value="1000_5000_UAH">1000-5000 UAH</option>
                <option value="more_than_5000_UAH">more than 5000 UAH</option> --}}
                {{!! $options !!}}
            </select>
            <button type="submit" class="btn btn-primary my-1">Filter</button>
        </form>
    </div>
    <div class="card-body">
        <table class="table">
            <thead>
                <tr>
                    <th scope="col">ID</th>
                    <th scope="col">Employer Name / Employer Login</th>
                    <th scope="col">Project Name</th>
                    <th scope="col">Budget</th>
                    <th scope="col">Categories</th>
                    <th scope="col">Link</th>
                </tr>
            </thead>
            <tbody>

                @foreach ($projects as $project)
                <tr>
                    <th scope="row">{{ $project->id }}</th>
                    <th>{{ $project->employer }}</th>
                    <th>{{ $project->project_name }}</th>
                    <th>{{ $project->budget_amount }}</th>
                    <th>{{-- $project->categories --}}
                        @if(App\Helpers\CategoriesHelper::output($project->categories) != 0)
                        @foreach (App\Helpers\CategoriesHelper::output($project->categories) as $category)
                        {{ $category }}
                        @endforeach
                        @endif
                    </th>
                    <th>
                        <a class="btn btn-link" href=" {{ $project->project_link }}" target="_blank">Go</a>

                    </th>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<script>
    const ctx = document.getElementById('myChart').getContext('2d');
    const myChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: {!! json_encode($pie_lables) !!},
            datasets: [{
                label: '# of Votes',
                data: {!! json_encode($pie_data) !!},
                backgroundColor: [
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(153, 102, 255, 0.2)',
                    'rgba(255, 159, 64, 0.2)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });
</script>
@endsection