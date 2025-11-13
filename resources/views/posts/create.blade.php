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
                    <h1 class="text-3xl my-2">Create Post</h1>
                    @if ($errors->any())
                        <div class="mb-4 p-4 rounded-md bg-red-50 border border-red-200">
                            <ul class="list-disc list-inside text-sm text-red-600">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
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
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 my-2 px-4 rounded shadow-lg">Save</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>


