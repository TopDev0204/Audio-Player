<script>
export default {
    data() {
        return {
            songs: [],
        };
    },
    mounted() {
        this.fetchSongs();
    },
    computed: {
        backgroundUrl() {
            return `background.png`;
        },
    },
    methods: {
        fetchSongs() {
            fetch("/api/songs")
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((data) => {
                    console.log(data);
                    this.songs = data;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        // playSong(id) {
        //     axios.get(`/api/songs/${id}/play`)
        //         .then(response => {
        //             const song = response.data;
        //             this.songs = this.songs.map(s => {
        //                 if (s.id === song.id) {
        //                     s.playing = !s.playing;
        //                 } else {
        //                     s.playing = false;
        //                 }
        //                 return s;
        //             });
        //         })
        //         .catch(error => {
        //             console.error(error);
        //         });
        // },
    },
};
</script>

<template>
    <div
        class="m-auto w-[400px] min-h-[100%] bg-dots-darker bg-center bg-gray-100 dark:bg-dots-lighter dark:bg-gray-900 selection:bg-red-500 selection:text-white border border-grey-400 rounded-lg"
    >
        <div class="w-100">
            <img
                :src="backgroundUrl"
                class="w-100 h-100 rounded-tl-lg rounded-tr-lg"
            />
        </div>
        <div class="w-100 h-[300px] overflow-auto px-2 py-1 songlist">
            <ul>
                <li
                    v-for="song in songs"
                    :key="song.id"
                    class="text-white my-1 px-2 py-1 cursor-pointer hover:border-b border-sky-400"
                >
                    <div class="flex items-center justify-between">
                        <!-- <button
                            @click="playSong(song.id)"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded mr-2"
                        >
                            {{ song.playing ? 'Pause' : 'Play' }}
                        </button> -->
                        <div>
                            <div class="text-sm font-bold">
                                {{ song.title }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ song.artist }}
                            </div>
                        </div>
                        <div class="text-xs text-gray-400">
                            <i class="fa" :class="song.playing ? 'play' : ''">
                            </i>
                        </div>
                    </div>
                </li>
            </ul>
        </div>
        <div class="w-100 text-center text-white">
            <h1 class="text-lg font-extrabold">Shape of you</h1>
            <h3 class="text-sm text-gray-400">Ed Sheeran</h3>
        </div>
        <div class="w-80 text-center m-auto items-center my-2 text-white">
            <input type="range" id="seekslider" value="0" />
            <div class="w-100 flex items-center justify-between my-1">
                <div class="text-xs text-gray-400 curtime">00:00</div>
                <div class="text-xs text-gray-400 durtime">00:00</div>
            </div>
        </div>
        <div class="w-100 flex items-center justify-center text-white py-3">
            <button
                class="w-[30px] h-[30px] border border-gray-300 p-1 rounded-full mx-1"
            >
                <i class="fa fa-random" aria-hidden="true"></i>
            </button>
            <button
                class="w-[30px] h-[30px] border border-gray-300 p-1 rounded-full mx-1"
            >
                <i class="fa fa-backward-step"></i>
            </button>
            <button
                class="w-[60px] h-[60px] border border-gray-300 p-1 rounded-full mx-1"
            >
                <i class="fa fa-play"></i>
            </button>
            <button
                class="w-[30px] h-[30px] border border-gray-300 p-1 rounded-full mx-1"
            >
                <i class="fa fa-forward-step"></i>
            </button>
        </div>
    </div>
</template>

<style scoped>
.songlist::-webkit-scrollbar {
    width: 8px;
    background-color: rgb(107 114 128);
}
.songlist::-webkit-scrollbar-thumb {
    width: 10px;
    height: 10px;
    background-color: rgb(31 41 55);
}
#seekslider {
    width: 100%;
    height: 3px;
}
</style>
