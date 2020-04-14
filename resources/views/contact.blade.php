@extends('layouts.app')

@section('content')
    <h1>Contacts</h1>
    <p>Hello, this is contacts</p>

    @can('home.secret')
        <a href="{{ route('secret') }}">
            <p>Spetial contact details</p>
        </a>
    @endcan
@endsection
