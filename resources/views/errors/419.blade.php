@extends('errors::minimal')

@section('title', __('Page Expired'))
@section('code', '419')
@section('message')
    {{ __('Page Expired') }}
    <br  /> <a href="{{ route('guest')}}" class="btn btn-danger">Go Back Home</a> 
@endsection

