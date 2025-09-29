<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Upload Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Upload Center</h2>
    @can('register format')
        <a href="{{ route('register.form') }}">Register New Format</a>
    @endcan

    <hr>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('upload.handle') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="mapping_code" class="form-label">Select Mapping Format</label>
            <select name="mapping_code" id="mapping_code" class="form-select" required>
                <option value="">-- Choose Format --</option>
                @foreach($mappings as $mapping)
                    <option value="{{ $mapping->code }}">{{ $mapping->description }} ({{$mapping->code}})</option>
                @endforeach
            </select>
        </div>
        <div class="mb-3">
            <label for="file" class="form-label">Choose Excel File</label>
            <input class="form-control" type="file" name="file" id="file" required>
        </div>
        <button type="submit" class="btn btn-primary">Upload</button>
    </form>
</div>
</body>
</html>