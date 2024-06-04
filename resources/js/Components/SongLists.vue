<script>
export default {
    data() {
        return {
            songs: [],
            play: {}, // playing song
            playedList: [], //played songs list
            playMethod: true, //true: random, false: regular
            audio: undefined,
            currentSeconds: 0,
            durationSeconds: 0,
            buffered: 0,
            innerLoop: false,
            loaded: false,
            playing: false,
        };
    },
    mounted() {
        this.fetchSongs();
        this.audio = this.$refs.audioFile;
        if (this.audio) {
            this.audio.addEventListener("timeupdate", this.update);
            this.audio.addEventListener("loadeddata", this.load);
            this.audio.addEventListener("buffered", this.update);
            this.audio.addEventListener("pause", () => {
                this.playing = false;
            });
            this.audio.addEventListener("play", () => {
                this.playing = true;
            });
        }
    },
    computed: {
        backgroundUrl() {
            return `background.png`;
        },
        percentBuffered() {
            return (this.buffered / this.durationSeconds) * 100;
        },
        percentComplete() {
            return (this.currentSeconds / (this.durationSeconds ?? 1)) * 100;
        },
    },
    methods: {
        convertTimeHHMMSS(val) {
            const hhmmss = new Date(val * 1000).toISOString().substr(11, 8);
            return hhmmss.indexOf("00:") === 0 ? hhmmss.substr(3) : hhmmss;
        },
        update() {
            this.currentSeconds = this.audio.currentTime;
            // this.buffered = this.audio.buffered.end(0);
            if (this.audio.currentTime === this.audio.duration) {
                this.handleOtherPlay("next");
            }
        },
        load() {
            if (this.audio.readyState >= 2) {
                this.loaded = true;
                this.durationSeconds = parseInt(this.audio.duration);
                this.playing = this.autoPlay;
                this.audio.play();
                return this.playing;
            }
            throw new Error("Failed to load sound file.");
        },
        seek(e) {
            if (!this.loaded) return;
            const val = e.target.value;
            this.audio.currentTime = (val / 100) * this.audio.duration;
        },
        fetchSongs() {
            fetch("/api/songs")
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((data) => {
                    this.songs = data;
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        playSong(id) {
            fetch(`/api/songs/play_or_stop/${id}`, {
                method: "put",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify({
                    currentId: this.play.id,
                }),
            })
                .then((response) => {
                    if (!response.ok) {
                        throw new Error("Network response was not ok");
                    }
                    return response.json();
                })
                .then((data) => {
                    this.play = data;
                    this.playing = data.playing;
                    this.songs = this.songs.map((s) => {
                        if (s.id === data.id) {
                            s.playing = data.playing;
                        } else {
                            s.playing = false;
                        }
                        return s;
                    });
                    if (!data.playing) {
                        this.audio.pause();
                    } else if (data.playing && this.loaded) {
                        this.audio.play();
                    }
                })
                .catch((error) => {
                    console.error(error);
                });
        },
        chnageMethod() {
            this.playMethod = !this.playMethod;
        },
        handleOtherPlay(e) {
            if (this.playMethod) {
                const id = this.randomPlay();
                return this.playSong(id);
            }
            const idx = this.songs.findIndex(
                (song) => song.id === this.play.id
            );
            if (e === "next") {
                const nextIdx = idx === this.songs.length - 1 ? 0 : idx;
                return this.playSong(this.songs[nextIdx + 1].id);
            } else {
                const prevIdx = idx === 1 ? this.songs.length : idx;
                return this.playSong(this.songs[prevIdx - 1].id);
            }
        },
        randomPlay() {
            const randomIndex = Math.floor(Math.random() * this.songs.length);
            return this.songs[randomIndex].id;
        },
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
                    class="text-white my-1 px-2 py-1 hover:border-b hover:border-gray-400"
                    :class="
                        song.id === play.id ? 'border-b border-gray-400' : ''
                    "
                >
                    <div class="flex items-center justify-between">
                        <div>
                            <div class="text-sm font-bold">
                                {{ song.title }}
                            </div>
                            <div class="text-xs text-gray-400">
                                {{ song.artist }}
                            </div>
                        </div>
                        <button
                            class="text-xs text-gray-400 border border-gray-400 p-1 rounded-full w-[25px] h-[25px] hover:text-white hover:border-white"
                            @click="playSong(song.id)"
                        >
                            <i
                                class="fa"
                                :class="song.playing ? 'fa-stop' : 'fa-play'"
                            >
                            </i>
                        </button>
                    </div>
                </li>
            </ul>
        </div>
        <div class="w-100 text-center text-white">
            <h1 class="text-lg font-extrabold">{{ play.title }}</h1>
            <h3 class="text-sm text-gray-400">{{ play.artist }}</h3>
        </div>
        <div class="w-80 text-center m-auto items-center my-2 text-white">
            <input
                type="range"
                id="seekslider"
                :value="percentComplete"
                @change="seek"
            />
            <div class="w-100 flex items-center justify-between my-1">
                <div class="text-xs text-gray-400 curtime">
                    {{ convertTimeHHMMSS(currentSeconds) }}
                </div>
                <div class="text-xs text-gray-400 durtime">
                    {{ convertTimeHHMMSS(durationSeconds) }}
                </div>
            </div>
            <audio
                class="none"
                ref="audioFile"
                :src="play.file_path"
            ></audio>
        </div>
        <div class="w-100 flex items-center justify-center text-white py-3">
            <button
                class="w-[30px] h-[30px] border border-gray-300 p-1 rounded-full mx-1"
                @click="chnageMethod"
            >
                <i
                    class="fa"
                    :class="playMethod ? 'fa-random' : 'fa-bars'"
                    aria-hidden="true"
                ></i>
            </button>
            <button
                class="w-[30px] h-[30px] border border-gray-300 p-1 rounded-full mx-1"
                @click="handleOtherPlay('prev')"
            >
                <i class="fa fa-backward-step"></i>
            </button>
            <button
                class="w-[60px] h-[60px] border border-gray-300 p-1 rounded-full mx-1"
                @click="playSong(play.id)"
            >
                <i class="fa" :class="play.playing ? 'fa-stop' : 'fa-play'"></i>
            </button>
            <button
                class="w-[30px] h-[30px] border border-gray-300 p-1 rounded-full mx-1"
                @click="handleOtherPlay('next')"
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
input[type="range"]::-webkit-slider-runnable-track {
    width: 100%;
    height: 3px;
    cursor: pointer;
    background: linear-gradient(
        to right,
        rgba(0, 125, 181, 0.6) var(--buffered-width),
        rgba(0, 125, 181, 0.2) var(--buffered-width)
    );
}
input[type="range"]::before {
    position: absolute;
    content: "";
    top: 8px;
    left: 0;
    width: var(--seek-before-width);
    height: 3px;
    background-color: #007db5;
    cursor: pointer;
}
input[type="range"]::-webkit-slider-thumb {
    position: relative;
    -webkit-appearance: none;
    box-sizing: content-box;
    border: 1px solid #007db5;
    height: 15px;
    width: 15px;
    border-radius: 50%;
    background-color: #fff;
    cursor: pointer;
    margin: -7px 0 0 0;
}
input[type="range"]:active::-webkit-slider-thumb {
    transform: scale(1.2);
    background: #007db5;
}
</style>
