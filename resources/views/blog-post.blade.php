@extends('layouts.app')

@section('content')
    {!! $welcome !!}{{ $data['title'] }}<br>
    {{ $data['text'] }}

@endsection('content')

