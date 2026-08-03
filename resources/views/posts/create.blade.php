<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">

            <h2 class="font-semibold text-xl text-gray-800 leading-tight mb-6">
                Write a New Post
            </h2>

            @if ($errors->any())
                <div class="bg-red-50 border border-red-200 text-red-700 p-4 rounded-lg mb-6">
                    <p class="font-semibold mb-2">Please fix the following:</p>
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('posts.store') }}" class="bg-white p-8 rounded-lg shadow space-y-6">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-gray-700 mb-1">Title</label>
                    <input type="text" id="title" name="title" value="{{ old('title') }}"
                           class="w-full border-gray-300 rounded-md shadow-sm">
                    @error('title')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category_id" class="block text-sm font-medium text-gray-700 mb-1">Category</label>
                    <select id="category_id" name="category_id" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">— Select a category —</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" @selected(old('category_id') == $category->id)>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="series_id" class="block text-sm font-medium text-gray-700 mb-1">Series (optional)</label>
                    <select id="series_id" name="series_id" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="">— No series —</option>
                        @foreach ($series as $s)
                            <option value="{{ $s->id }}" @selected(old('series_id') == $s->id)>
                                {{ $s->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('series_id')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="excerpt" class="block text-sm font-medium text-gray-700 mb-1">Excerpt (optional)</label>
                    <input type="text" id="excerpt" name="excerpt" value="{{ old('excerpt') }}"
                           class="w-full border-gray-300 rounded-md shadow-sm">
                    @error('excerpt')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="content" class="block text-sm font-medium text-gray-700 mb-1">Content</label>
                    <textarea id="content" name="content" rows="12"
                              class="w-full border-gray-300 rounded-md shadow-sm">{{ old('content') }}</textarea>
                    @error('content')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                    <select id="status" name="status" class="w-full border-gray-300 rounded-md shadow-sm">
                        <option value="draft" @selected(old('status') == 'draft')>Draft</option>
                        <option value="published" @selected(old('status') == 'published')>Published</option>
                        <option value="archived" @selected(old('status') == 'archived')>Archived</option>
                    </select>
                    @error('status')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="tags" class="block text-sm font-medium text-gray-700 mb-1">Tags</label>
                    <select id="tags" name="tags[]" multiple class="w-full border-gray-300 rounded-md shadow-sm" size="6">
                        @foreach ($tags as $tag)
                            <option value="{{ $tag->id }}" @selected(in_array($tag->id, old('tags', [])))>
                                {{ $tag->name }}
                            </option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-1">Hold Ctrl (Windows) or Cmd (Mac) to select multiple.</p>
                    @error('tags')
                        <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-md hover:bg-indigo-700">
                        Publish
                    </button>
                    <a href="{{ route('home') }}" class="px-6 py-2 text-gray-600 hover:text-gray-900">
                        Cancel
                    </a>
                </div>

            </form>
        </div>
    </div>
</x-app-layout>