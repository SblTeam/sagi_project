<!DOCTYPE html>
<html>
<head>
    <title>Import Errors</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
    <div class="container mt-5">
        <h2>Import Errors</h2>
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('importErrors'))
            <div class="alert alert-danger">
                <ul>
                    @foreach (session('importErrors') as $error)
                        <li>{!! $error !!}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <a href="{{ route('masters-ItemMaster') }}" class="btn btn-primary">Back to Import Page</a>
    </div>
</body>
</html>
