import Plyr from 'plyr';
import 'select2';
import 'alpinejs';
import Hls from 'hls.js';
import $ from 'jquery';

window.$ = window.jQuery = $;


$(() => {

    $('.select2-tides').select2();

    $('.select2-tides-tags').select2({
        allowClear: true,
        placeholder: 'Add a tag',
        tags: true,
        minimumInputLength: 2,
        ajax:{
            url: "/api/tags/",
            delay: 250,
            data: function (params) {
                return {
                    query: params.term, // search term
                    page: params.page
                };
            },
            processResults: function (data, params) {
                params.page = params.page || 1;
                return  {
                    results: $.map(data, function (obj) {
                        return {id: obj.name, text: obj.name};
                    }),
                    pagination: {
                        more: (params.page * 30) < data.total_count
                    }
                };
            },
        }
    });
});


document.addEventListener("DOMContentLoaded", () => {
    const video = document.querySelector("video");
    const source = video.getElementsByTagName("source")[0].src;

    // For more options see: https://github.com/sampotts/plyr/#options
    // captions.update is required for captions to work with hls.js
    const defaultOptions = {};

    if (Hls.isSupported()) {
        // For more Hls.js options, see https://github.com/dailymotion/hls.js
        const hls = new Hls();
        hls.loadSource(source);

        // From the m3u8 playlist, hls parses the manifest and returns
        // all available video qualities. This is important, in this approach,
        // we will have one source on the Plyr player.
        hls.on(Hls.Events.MANIFEST_PARSED, function (event, data) {

            // Transform available levels into an array of integers (height values).
            const availableQualities = hls.levels.map((l) => l.height)
            defaultOptions.language = 'de';
            defaultOptions.iconUrl = '/css/plyr.svg';
            defaultOptions.loadSripte = false;

            // Add new qualities to option
            defaultOptions.quality = {
                default: availableQualities[0],
                options: availableQualities,
                // this ensures Plyr to use Hls to update quality level
                forced: true,
                onChange: (e) => updateQuality(e),
            }


            // Initialize here
            const player = new Plyr(video, defaultOptions);
        });
        hls.attachMedia(video);
        window.hls = hls;
    } else {
        // default options with no quality update in case Hls is not supported
        const player = new Plyr(video, {
            language: 'de',
            iconUrl: '/css/plyr.svg',
            loadSprite: false,
        });
    }
    function updateQuality(newQuality) {
        window.hls.levels.forEach((level, levelIndex) => {
            if (level.height === newQuality) {
                console.log("Found quality match with " + newQuality);
                window.hls.currentLevel = levelIndex;
            }
        });
    }
});

