<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Posts') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="p-4 sm:p-8 bg-white shadow sm:rounded-lg">
                <div class="max-w-xl">
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
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


