<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>PDF Report</title>
</head>
<body>
    @foreach ($photos as $photo)
    <div style="page-break-after: always;">
    
        <img src="{{ $photo }}" alt="Image" style="width: 720pt; height: 1020pt; object-fit: cover;">
    </div>
    @endforeach
</body>
</html>