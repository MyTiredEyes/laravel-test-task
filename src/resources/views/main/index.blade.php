@extends('layouts.default')

@section('content')

    <h1>Main page</h1>

    <ul style="column-count: 5;">
        @foreach($cities as $city)
            <li>
                <a href="{{ url("/{$city->slug}") }}" @class([
                    'fw-bold' => session('city') && session('city.slug') == $city->slug
                ])>
                    {{ $city->title }}
                </a>
            </li>
        @endforeach
    </ul>

@endsection
