@extends('layouts.app')

@section('content')
<div id="app">
    {{-- Komponen Vue akan di-mount di sini. Sesuaikan nama komponen jika berbeda. --}}
    {{-- Pastikan nama komponen di sini sama dengan yang didaftarkan di resources/js/app.js --}}
    <upload-center-component 
        :can-create-mapping="{{ auth()->user()->can('create mapping') ? 'true' : 'false' }}"
    ></upload-center-component>
</div>
@endsection