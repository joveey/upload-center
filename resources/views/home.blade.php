@extends('layouts.app')

@section('content')
<div class="container" id="app">
    {{-- Komponen Vue dipanggil di sini dengan properti yang diperlukan --}}
    <upload-center 
        :can-create-mapping="{{ auth()->user()->can('create mapping') ? 'true' : 'false' }}"
    ></upload-center>
    
</div>
@endsection