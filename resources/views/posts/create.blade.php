<!DOCTYPE html>
<html>
<head>
    <title>Create Post</title>
</head>
<body>
    <h1>Create Post</h1>

    <form action="{{ route('posts.store') }}" method="POST">
        @csrf
        <p>
            <label>Title:</label><br>
            <input type="text" name="title" value="{{ old('title') }}">
        </p>
        <p>
            <label>Content:</label><br>
            <textarea name="content">{{ old('content') }}</textarea>
        </p>
        <button type="submit">Save</button>
    </form>
</body>
</html>
