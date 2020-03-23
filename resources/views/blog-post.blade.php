@extends('layout')

@section('content')
    {!! $welcome !!}{{ $data['title'] }}<br>
    {{ $data['text'] }}

@endsection('content')

