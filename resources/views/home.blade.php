@extends('layouts.app')

@section('content')

@guest
<input type="hidden" id="isLoggedIn" name="isLoggedIn" value="false">
@else
    <input type="hidden" id="isLoggedIn" name="isLoggedIn" value="true">
@endif

<div id="app">
    <router-view></router-view>
</div>

@endsection