@extends('layouts.app')

@section('content')
<div id="app">
    {{-- Passing boolean tanpa tanda kutip --}}
    <upload-center-component 
        :can-create-mapping="{{ auth()->user()->can('create mapping') ? 'true' : 'false' }}"
    ></upload-center-component>
</div>

{{-- Debugging: Cek permission di console --}}
<script>
    console.log('User can create mapping:', {{ auth()->user()->can('create mapping') ? 'true' : 'false' }});
</script>
@endsection