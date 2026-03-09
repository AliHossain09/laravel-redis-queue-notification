<!DOCTYPE html>
<html>
<head>
    <title>Laravel Redis Queue Notification</title>

    <script src="https://cdn.tailwindcss.com"></script>

</head>
<body class="bg-gray-100">

<div class="max-w-3xl mx-auto mt-10">

<h1 class="text-3xl font-bold mb-6">
Laravel Redis Queue Notification
</h1>

<form method="POST" action="/posts" class="bg-white p-6 rounded shadow mb-10">

@csrf

<div class="mb-4">

<label class="block font-semibold mb-2">Title</label>

<input 
type="text"
name="title"
class="w-full border p-2 rounded"
required>

</div>

<div class="mb-4">

<label class="block font-semibold mb-2">Content</label>

<textarea
name="content"
class="w-full border p-2 rounded"
rows="4"
required></textarea>

</div>

<button 
class="bg-blue-500 text-white px-4 py-2 rounded">

Create Post

</button>

</form>

<div class="bg-white p-6 rounded shadow">

<h2 class="text-xl font-bold mb-4">

Posts List

</h2>

@foreach($posts as $post)

<div class="border-b py-3">

<h3 class="font-bold">

{{ $post->title }}

</h3>

<p class="text-gray-600">

{{ $post->content }}

</p>

</div>

@endforeach

</div>

</div>

</body>
</html>