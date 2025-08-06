@extends('layouts.pdf')
@section('content')
<style>
    .test_title {
        font-size: 20px;
        color: {{ $reports_settings['test_title']['color'] }} !important;
        font-size: {{ $reports_settings['test_title']['font-size'] }} !important;
        font-family: {{ $reports_settings['test_title']['font-family'] }} !important;
    }

   

    .subtitle {
        font-size: 15px;
    }

    .comment {
        margin-top: 20px;
    }

    .test {
        /* page-break-inside: avoid; */
        margin: 5%;
        
    }

    .transparent {
        border-color: white;
    }

    .transparent th {
        border-color: white;
    }

    .test_head td, th {
        border: 1px solid #0000;
    }
.tabletest td, th {
        border: 1px solid #0000;
    }
    .no-border {
        border-color: white;
    }

    .sensitivity {
        margin: 20px;
    }

    .test_name {
        color: {{ $reports_settings['test_name']['color'] }} !important;
        font-size: {{ $reports_settings['test_name']['font-size'] }} !important;
        font-family: {{ $reports_settings['test_name']['font-family'] }} !important;
    }

    .test_head th {
        color: {{ $reports_settings['test_head']['color'] }} !important;
        font-size: {{ $reports_settings['test_head']['font-size'] }} !important;
        font-family: {{ $reports_settings['test_head']['font-family'] }} !important;
    }

    .unit {
        color: {{ $reports_settings['unit']['color'] }} !important;
        font-size: {{ $reports_settings['unit']['font-size'] }} !important;
        font-family: {{ $reports_settings['unit']['font-family'] }} !important;
    }

    .reference_range {
        color: {{ $reports_settings['reference_range']['color'] }} !important;
        font-size: {{ $reports_settings['reference_range']['font-size'] }} !important;
        font-family: {{ $reports_settings['reference_range']['font-family'] }} !important;
    }

    .result {
        color: {{ $reports_settings['result']['color'] }} !important;
        font-size: {{ $reports_settings['result']['font-size'] }} !important;
        font-family: {{ $reports_settings['result']['font-family'] }} !important;
    }

    .status {
        color: {{ $reports_settings['status']['color'] }} !important;
        font-size: {{ $reports_settings['status']['font-size'] }} !important;
        font-family: {{ $reports_settings['status']['font-family'] }} !important;
    }

    .comment th, .comment td {
        color: {{ $reports_settings['comment']['color'] }} !important;
        font-size: {{ $reports_settings['comment']['font-size'] }} !important;
        font-family: {{ $reports_settings['comment']['font-family'] }} !important;
    }

    .antibiotic_name {
        color: {{ $reports_settings['antibiotic_name']['color'] }} !important;
        font-size: {{ $reports_settings['antibiotic_name']['font-size'] }} !important;
        font-family: {{ $reports_settings['antibiotic_name']['font-family'] }} !important;
    }

    .sensitivity {
        color: {{ $reports_settings['sensitivity']['color'] }} !important;
        font-size: {{ $reports_settings['sensitivity']['font-size'] }} !important;
        font-family: {{ $reports_settings['sensitivity']['font-family'] }} !important;
    }

    .commercial_name {
        color: {{ $reports_settings['commercial_name']['color'] }} !important;
        font-size: {{ $reports_settings['commercial_name']['font-size'] }} !important;
        font-family: {{ $reports_settings['commercial_name']['font-family'] }} !important;
    }
    .page-break-before {
        page-break-before: always;
    }
</style>


<div class="printable">
@php
    $count = 0;
@endphp

<!-- Group and Display Tests by Category -->
@if(count($group['tests']))
    @php
        $groupedTests = collect($group['tests'])->groupBy(function ($test) {
            return $test['test']['category']['catogery'];
        });
    @endphp

    @foreach ($groupedTests as $categoryName => $tests)
        @if($count > 0)
        @endif
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <h3 class="test_title" align="center">{{ $categoryName }}</h3>
                    <table class="table test" align="center">
                        <thead>
                            <tr class="transparent">
                                <th colspan="5"></th>
                            </tr>
                            <tr class="test_head">
                                <th width="30%" class="text-left">Test</th>
                                <th width="23.3%">Result</th>
                                <th width="23.3%">Unit</th>
                                <th width="23.3%">Normal Range</th>
                            </tr>
                        </thead>
                        <tbody class="table-bordered tabletest">
                            @foreach ($tests as $test)
                                @php
                                    $count++;
                                @endphp
                                @foreach($test["results"] as $result)
                                    @if(isset($result['component']))
                                        @if($result['component']['title'])
                                            <tr>
                                                <td colspan="5" class="component_title test_name">
                                                    <b>{{ $result['component']['name'] }}</b>
                                                </td>
                                            </tr>
                                        @else
                                            <tr>
                                                <td class="text-capitalize test_name">{{ $result["component"]["name"] }}</td>
                                                <td align="center" class="result">{{ $result["result"] }}</td>
                                                <td align="center" class="unit">{{ $result["component"]["unit"] }}</td>
                                                <td align="center" class="reference_range">{!! $result["component"]["reference_range"] !!}</td>
                                            </tr>
                                        @endif
                                    @endif
                                @endforeach
                                @if(isset($test['comment']))
                                    <tr class="comment">
                                        <td colspan="5">
                                            <b>Comment :</b> {{ $test['comment'] }}
                                        </td>
                                    </tr>
                                @endif
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endforeach
@endif

<!-- Display Cultures -->
@foreach($group['cultures'] as $culture)
    @if($count > 0)
    @endif

    <h5 class="test_title" align="center">{{ $culture['culture']['name'] }}</h5>

    <table class="table" width="100%">
        <tbody>
            @foreach($culture['culture_options'] as $culture_option)
                @if(isset($culture_option['value']) && isset($culture_option['culture_option']))
                    <tr>
                        <th class="no-border test_name" width="10px" nowrap="nowrap" align="left">
                            <span class="option_title">{{ $culture_option['culture_option']['value'] }} :</span>
                        </th>
                        <td class="no-border result">{{ $culture_option['value'] }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>
 <table class="table table-bordered tabletest sensitivity" width="100%">
        <thead class="test_head">
            <tr>
                <th align="left">Name</th>
                                <th align="left">Shortcut</th>

                <th align="left">Sensitivity</th>
            </tr>
        </thead>
        <tbody>
            @foreach($culture['antibiotics'] as $antibiotic)
            <tr>
                <td width="200px" nowrap="nowrap" align="left">
                    {{$antibiotic['antibiotic']['name']}}
                </td>
                 <td width="120px" nowrap="nowrap" align="left">
                    {{$antibiotic['antibiotic']['shortcut']}}
                </td>
                <td width="120px" nowrap="nowrap" align="left">
                    {{$antibiotic['sensitivity']}}
                </td>
            </tr>
            @endforeach

           
        </tbody>
    </table>
    @if(isset($culture['comment']))
        <table width="100%" class="comment">
            <tbody>
                <tr>
                    <td width="10px" nowrap="nowrap no-border"><b>Comment</b> :</td>
                    <td>{{ $culture['comment'] }}</td>
                </tr>
            </tbody>
        </table>
    @endif

    @php
        $count++;
    @endphp
@endforeach

</div>
@endsection
