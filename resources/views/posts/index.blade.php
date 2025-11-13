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
                    <h1 class="text-3xl my-2">All Posts</h1>

                    <a href="{{ route('posts.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 my-2 px-4 rounded shadow-lg">Create New Post</a>

                    @if (session('success'))
                        <p style="color: green;margin-top:15px">{{ session('success') }}</p>
                    @endif

                    <div class="overflow-x-auto bg-white shadow-md rounded-lg mt-5">
                        <table class="min-w-full border border-gray-200 text-sm text-left text-gray-700">
                            <thead class="bg-gray-100 text-gray-900 uppercase text-xs">
                                <tr>
                                    <th class="px-6 py-3 border-b">ID</th>
                                    <th class="px-6 py-3 border-b">Title</th>
                                    <th class="px-6 py-3 border-b">Content</th>
                                    <th class="px-6 py-3 border-b text-center">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-100">
                                @foreach ($posts as $post)
                                    <tr class="hover:bg-gray-50 transition">
                                        <td class="px-6 py-4">{{ $post->id }}</td>
                                        <td class="px-6 py-4 font-medium text-gray-800">{{ $post->title }}</td>
                                        <td class="px-6 py-4 text-gray-600">{{ $post->content }}</td>
                                        <td class="px-6 py-4 text-center">
                                            <a href="{{ route('posts.edit', $post->id) }}"
                                            class="text-blue-600 hover:text-blue-800 font-semibold mr-3">
                                                Edit
                                            </a>
                                            <form action="{{ route('posts.destroy', $post->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        onclick="return confirm('Delete this post?')"
                                                        class="text-red-600 hover:text-red-800 font-semibold">
                                                    Delete
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>