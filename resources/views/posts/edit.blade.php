<x-app-layout>
    <div class="py-12 max-w-3xl mx-auto px-4">

        <h1 class="text-2xl font-bold mb-6">Edit Post</h1>

        {{-- Show validation errors, if any --}}
        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 text-red-700 rounded">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('posts.update', $post) }}">
            @csrf
            @method('PUT') {{-- fakes a PUT request, required for update routes --}}

            {{-- Title --}}
            <div class="mb-4">
                <label for="title" class="block font-medium mb-1">Title</label>
                <input type="text" id="title" name="title"
                       value="{{ old('title', $post->title) }}"
                       class="w-full border-gray-300 rounded">
                @error('title')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Excerpt --}}
            <div class="mb-4">
                <label for="excerpt" class="block font-medium mb-1">Excerpt</label>
                <input type="text" id="excerpt" name="excerpt"
                       value="{{ old('excerpt', $post->excerpt) }}"
                       class="w-full border-gray-300 rounded">
                @error('excerpt')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Content --}}
            <div class="mb-4">
                <label for="content" class="block font-medium mb-1">Content</label>
                <textarea id="content" name="content" rows="10"
                          class="w-full border-gray-300 rounded">{{ old('content', $post->content) }}</textarea>
                @error('content')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Category --}}
            <div class="mb-4">
                <label for="category_id" class="block font-medium mb-1">Category</label>
                <select id="category_id" name="category_id" class="w-full border-gray-300 rounded">
                    <option value="">Select category…</option>
                    @foreach ($categories as $category)
                        <option value="{{ $category->id }}"
                            {{ old('category_id', $post->category_id) == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                @error('category_id')
                    <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            {{-- Series (optional) --}}
            <div class="mb-4">
                <label for="series_id" class="block font-medium mb-1">Series</label>
                <select id="series_id" name="series_id" class="w-full border-gray-300 rounded">
                    <option value="">No series</option>
                    @foreach ($series as $s)
                        <option value="{{ $s->id }}"
                            {{ old('series_id', $post->series_id) == $s->id ? 'selected' : '' }}>
                            {{ $s->title }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Tags (checkboxes are simplest to reason about) --}}
            <div class="mb-4">
                <label class="block font-medium mb-1">Tags</label>
                @foreach ($tags as $tag)
                    <label class="inline-flex items-center mr-4">
                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                            {{ in_array($tag->id, old('tags', $post->tags->pluck('id')->toArray())) ? 'checked' : '' }}>
                        <span class="ml-1">{{ $tag->name }}</span>
                    </label>
                @endforeach
            </div>

            {{-- Status --}}
            <div class="mb-6">
                <label for="status" class="block font-medium mb-1">Status</label>
                <select id="status" name="status" class="w-full border-gray-300 rounded">
                    @foreach (['draft', 'published', 'archived'] as $option)
                        <option value="{{ $option }}"
                            {{ old('status', $post->status) === $option ? 'selected' : '' }}>
                            {{ ucfirst($option) }}
                        </option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded">
                Update Post
            </button>
            <a href="{{ route('home') }}" class="ml-2 text-gray-600">Cancel</a>

        </form>
    </div>
</x-app-layout>