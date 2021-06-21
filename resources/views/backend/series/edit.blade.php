@extends('layouts.backend')

@section('content')
        <div class="flex pb-2 font-semibold border-b border-black font-2xl">
            Edit {{ $series->title }} [ ID : {{ $series->id }}]<span
                class="text-xs italic pl-2 pt-1"> created at {{$series->created_at }} </span>
        </div>
        <div class="flex justify-center content-center content-between py-2 px-2">
            <form action="{{ $series->adminPath() }}"
                  method="POST"
                  class=" @if(auth()->user()->isAdmin()) w-4/5 @else w-full @endif"
            >
                @csrf
                @method('PATCH')

                <div class="grid grid-cols-8 gap-2 py-3">

                    <div class="flex content-center items-center">
                        <label class="block py-2 mr-6 font-bold text-gray-700 text-md"
                               for="title"
                        >
                            Title
                        </label>
                    </div>
                    <div class="col-span-7 w-4/5">
                        <input class="py-2 px-4 w-full leading-tight text-gray-700 bg-white rounded border-2 border-gray-200 appearance-none focus:outline-none focus:bg-white focus:border-blue-500"
                               type="text"
                               name="title"
                               id="title"
                               value="{{ $series->title }}"
                               required
                        >
                    </div>
                    @error('title')
                    <div class="col-span-8">
                        <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
                    </div>
                    @enderror

                    <div class="flex content-center items-center mb-6">
                        <label class="block py-2 mr-6 font-bold text-gray-700 text-md"
                               for="description"
                        >
                            Description
                        </label>
                    </div>
                    <div class="col-span-7 w-4/5">
                        <textarea class="py-2 px-4 w-full leading-tight text-gray-700 bg-white rounded border-2 border-gray-200 appearance-none focus:outline-none focus:bg-white focus:border-blue-500"
                                  type="text"
                                  rows="10"
                                  name="description"
                                  id="description"
                        > {{ $series->description }}</textarea>
                    </div>
                    @error('description')
                    <div class="col-span-8">
                        <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
                    </div>
                    @enderror

                    <div class="flex content-center items-center">
                        <label class="block py-2 mr-6 font-bold text-gray-700 text-md"
                               for="opencast_series_id"
                        >
                            Opencast Series ID
                        </label>
                    </div>
                    <div class="col-span-7 w-4/5">
                        <input class="py-2 px-4 w-full leading-tight text-gray-700 bg-white rounded border-2 border-gray-200 appearance-none focus:outline-none focus:bg-white focus:border-blue-500"
                               type="text"
                               name="opencast_series_id"
                               id="opencast_series_id"
                               disabled
                               value="{{ $series->opencast_series_id }}"
                               required
                        >
                    </div>
                    @error('opencast_series_id')
                    <div class="col-span-8">
                        <p class="mt-2 w-full text-xs text-red-500">{{ $message }}</p>
                    </div>
                    @enderror

                    <x-form.acl :model="$series"/>

                </div>

                <x-form.button :link="$link=false" type="submit" text="Update Series"/>

            </form>

            @if(auth()->user()->isAdmin())
                <div class="w-1/5">
                    Series owner is {{ $series->owner->name }}
                </div>
            @endif

        </div>

        <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
            More actions
        </div>
        <div class="flex items-center pt-3 space-x-6">
            <x-form.button :link="route('series.show',$series)" type="submit" text="Go to public page"/>

            <x-form.button :link="route('series.clip.create',$series)" type="submit" text="Add new clip"/>

            <form action="{{$series->adminPath()}}"
                  method="POST">
                @csrf
                @method('DELETE')
                <x-form.button :link="$link=false" type="delete" text="Delete Series"/>
            </form>
        </div>
        @if(isset($opencastSeriesRunningWorkflows['workflows']) && $opencastSeriesRunningWorkflows['workflows']['totalCount'] > 0)
        <div class="flex pt-8 pb-2 font-semibold border-b border-black font-2xl">
            Opencast running events
        </div>
            <ul>
                @foreach($opencastSeriesRunningWorkflows['workflows']['workflow'] as $workflow)
                    <li>
                        {{ $workflow['mediapackage']['title'] }}
                    </li>
                @endforeach
            </ul>
        @endif
        @include('backend.clips.list')

    </div>
@endsection
