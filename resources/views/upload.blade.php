@extends('layouts.app')

@section('content')
<div id="app">
    {{-- Tag ini akan secara otomatis digantikan oleh komponen Vue kita --}}
    <upload-center-component></upload-center-component>
</div>
@endsection