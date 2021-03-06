<div class="flex pt-8 pb-2 font-semibold border-b border-black text-lg">
    Assets
</div>
<div class="flex">
    <ul class="pt-3 w-full">
        <li class="flex content-center items-center p-5 mb-4 bg-gray-400 rounded">
            <div class="pb-2 w-1/6 border-b border-black">ID</div>
            <div class="pb-2 w-3/6 border-b border-black">Saved path</div>
            <div class="pb-2 w-1/6 border-b border-black">Duration</div>
            <div class="pb-2 w-1/6 border-b border-black">Resolution</div>
            <div class="pb-2 w-1/6 border-b border-black">Actions</div>
        </li>

        @forelse($assets as $asset)
            <li class="flex content-center items-center p-5 mb-4 bg-gray-200 rounded">
                <div class="w-1/6"> {{ $asset->id }}</div>
                <div class="w-3/6"> {{ $asset->path }}</div>
                <div class="w-1/6"> {{ $asset->durationToHours() }}</div>
                <div class="w-1/6"> {{ $asset->width }} x {{ $asset->height }}</div>
                <div class="w-1/6 flex items-center align-items-center space-x-1">
                    <x-form.button :link="route('assets.download',$asset)" type="submit" text="Download"/>
                    <form method="POST"
                          action="{{$asset->path() }}"
                    >
                        @csrf
                        @method('DELETE')
                        <x-form.button :link="$link=false" type="delete" text="Delete"/>
                    </form>
                </div>
            </li>
        @empty
            No assets
        @endforelse
    </ul>
</div>
