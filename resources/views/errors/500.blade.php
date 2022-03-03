@extends('errors::minimal')

@section('title', __('Server Error'))
@section('code', '500')
@section('message')
    {{ __('Server Error') }}
     <br  /> <a href="{{ route('login')}}" class="btn btn-info">Refresh This Page</a> 
@endsection



