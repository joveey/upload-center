@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Register New Excel Mapping Format</div>

                <div class="card-body">
                    <form action="{{ route('register.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Mapping Code</label>
                            <input type="text" name="code" class="form-control" placeholder="e.g., spotify_users_monthly" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <input type="text" name="description" class="form-control" placeholder="e.g., Monthly Spotify Users Report" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Database Table Name</label>
                            <input type="text" name="table_name" class="form-control" placeholder="e.g., spotify_users" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Header starts at row</label>
                            <input type="number" name="header_row" class="form-control" value="1" min="1" required>
                        </div>

                        <hr>
                        <h4>Column Mapping</h4>
                        <div id="column-wrapper">
                            <div class="row mb-2">
                                <div class="col-md-5">
                                    <input type="text" name="columns[0][excel_col]" class="form-control" placeholder="Excel Column (e.g., A)" required>
                                </div>
                                <div class="col-md-5">
                                    <input type="text" name="columns[0][db_col]" class="form-control" placeholder="Database Column (e.g., user_name)" required>
                                </div>
                            </div>
                        </div>
                        <button type="button" id="add-column" class="btn btn-secondary btn-sm">+ Add More Column</button>
                        <hr>
                        <button type="submit" class="btn btn-primary">Save Mapping</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // ... (kode JavaScript Anda tetap sama persis, tidak perlu diubah) ...
    document.getElementById('add-column').addEventListener('click', function () {
        let index = document.querySelectorAll('#column-wrapper .row').length;
        let wrapper = document.getElementById('column-wrapper');
        let newRow = document.createElement('div');
        newRow.classList.add('row', 'mb-2');
        newRow.innerHTML = `
            <div class="col-md-5">
                <input type="text" name="columns[${index}][excel_col]" class="form-control" placeholder="Excel Column (e.g., A)" required>
            </div>
            <div class="col-md-5">
                <input type="text" name="columns[${index}][db_col]" class="form-control" placeholder="Database Column (e.g., user_name)" required>
            </div>
            <div class="col-md-2">
                <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove()">Remove</button>
            </div>
        `;
        wrapper.appendChild(newRow);
    });
</script>
@endsection