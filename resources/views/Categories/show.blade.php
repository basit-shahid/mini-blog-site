<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <div class="flex items-center justify-between mb-6">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                    Posts in {{ $category->name }}
                </h2>
            </div>

            @if ($posts->isEmpty())
                <div class="bg-white p-8 rounded-lg shadow text-center text-gray-500">
                    No posts published yet in this category.
                </div>
            @else
                <div class="space-y-6">
                    @foreach ($posts as $post)
                        <article class="bg-white p-6 rounded-lg shadow">
                            <div class="text-sm text-gray-500 mb-2">
                                {{ $post->published_at->format('M d, Y') }}
                                &middot;
                                {{ $post->reading_time }} min read
                            </div>

                            <h3 class="text-xl font-bold mb-2">
                                <a href="{{ route('posts.show', $post) }}" class="text-gray-900 hover:text-indigo-600">
                                    {{ $post->title }}
                                </a>
                            </h3>

                            @if ($post->excerpt)
                                <p class="text-gray-600 mb-3">{{ $post->excerpt }}</p>
                            @endif

                            <div class="flex items-center justify-between mt-4">
                                <div class="text-sm text-gray-500">
                                    by {{ $post->user->name }}
                                </div>

                                <div class="flex gap-2">
                                    <a href="{{ route('posts.show', $post) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-xs font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                                        View
                                    </a>

                                    @can('update', $post)
                                        <a href="{{ route('posts.edit', $post) }}" class="inline-flex items-center px-3 py-1.5 bg-white border border-gray-300 rounded-md text-xs font-medium text-gray-700 hover:bg-gray-50 transition shadow-sm">
                                            Edit
                                        </a>
                                    @endcan

                                    @can('delete', $post)
                                        <form method="POST" action="{{ route('posts.destroy', $post) }}" onsubmit="return confirm('Are you sure you want to delete this post?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center px-3 py-1.5 bg-red-50 border border-red-200 rounded-md text-xs font-medium text-red-600 hover:bg-red-100 transition shadow-sm">
                                                Delete
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </div>
                        </article>
                    @endforeach
                </div>

                <div class="mt-8">
                    {{ $posts->links() }}
                </div>
            @endif

        </div>
    </div>
</x-app-layout>