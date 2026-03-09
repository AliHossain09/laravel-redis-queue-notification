<!DOCTYPE html>
<html>
<head>
    <title>Laravel Redis Queue Notification</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-slate-100 text-slate-900">

<div class="mx-auto max-w-5xl px-4 py-10">

<div class="mb-8 flex items-center justify-between gap-4">
<div>
<p class="text-sm font-semibold uppercase tracking-[0.35em] text-slate-500">Redis Demo</p>
<h1 class="mt-2 text-3xl font-bold">
Laravel Redis Queue Notification
</h1>
</div>

<div class="relative">
<button
type="button"
data-notification-bell
class="relative rounded-full border border-slate-200 bg-white p-3 shadow-sm transition hover:bg-slate-50">
<span class="sr-only">Open notifications</span>
<svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-slate-700" fill="none" viewBox="0 0 24 24" stroke="currentColor">
<path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M14.857 17.082a23.848 23.848 0 0 1-5.714 0A8.967 8.967 0 0 1 6 16.139V11a6 6 0 1 1 12 0v5.139a8.967 8.967 0 0 1-3.143.943ZM9.5 17.5a2.5 2.5 0 0 0 5 0" />
</svg>
<span
data-notification-count
class="@if($notifications->isEmpty()) hidden @endif absolute -right-1 -top-1 min-w-6 rounded-full bg-rose-500 px-1.5 py-0.5 text-center text-xs font-bold text-white">
{{ $notifications->count() }}
</span>
</button>

<div
data-notification-panel
class="hidden absolute right-0 z-10 mt-3 w-80 overflow-hidden rounded-3xl border border-slate-200 bg-white shadow-xl">
<div class="border-b border-slate-100 px-5 py-4">
<h2 class="text-sm font-semibold text-slate-800">Live Notifications</h2>
<p class="mt-1 text-xs text-slate-500">Queue worker notification complete করলে এখানে চলে আসবে।</p>
</div>

<ul data-notification-list class="space-y-3 p-4">
@forelse($notifications as $notification)
<li class="rounded-2xl border border-slate-200 bg-slate-50 px-4 py-3">
<p class="text-sm font-medium text-slate-800">{{ $notification->message }}</p>
<p class="mt-1 text-xs text-slate-500">{{ $notification->created_at?->diffForHumans() }}</p>
</li>
@empty
<li data-notification-empty class="rounded-2xl border border-dashed border-slate-200 px-4 py-6 text-center text-sm text-slate-500">
No notifications yet.
</li>
@endforelse
</ul>
</div>
</div>
</div>

<div class="grid gap-8 lg:grid-cols-[1.1fr_0.9fr]">
<form method="POST" action="/posts" class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

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

<div class="rounded-3xl border border-slate-200 bg-white p-6 shadow-sm">

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

</div>

</body>
</html>
