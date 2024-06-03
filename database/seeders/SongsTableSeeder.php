<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Song;

class SongsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $songs = [
            [
                'artist' => 'Ed Sheeran',
                'title' => 'Shape of You',
                'file_path' => 'https://ia800708.us.archive.org/22/items/EdSheeranShapeOfYou_201811/Ed%20Sheeran%20-%20Shape%20Of%20You.mp3',
                'playing' => false
            ],
            [
                'artist' => 'Billie Eilish',
                'title' => 'Bad Guy',
                'file_path' => 'https://ia802809.us.archive.org/34/items/billie-eilish-bad-guy-lyrics-mp-3-320-k/Billie%20Eilish%20-%20bad%20guy%20%28Lyrics%29%28MP3_320K%29.mp3',
                'playing' => false
            ],
            [
                'artist' => 'Post Malone',
                'title' => 'Circles',
                'file_path' => 'https://ia802805.us.archive.org/30/items/y2mate.compostmalonecircleslyrics0sca9fp6zl8/y2mate.com%20-%20post_malone_circles_lyrics_0sca9FP6zl8.mp3',
                'playing' => false
            ],
            [
                'artist' => 'Taylor Swift',
                'title' => 'Shake It Off',
                'file_path' => 'https://ia903404.us.archive.org/34/items/taylor-swift-shake-it-off/taylor-swift-shake-it-off.mp3',
                'playing' => false
            ],
            [
                'artist' => 'The Weeknd',
                'title' => 'Blinding Lights',
                'file_path' => 'https://dn720302.ca.archive.org/0/items/the-weeknd-blinding-lights-official-audio_202103/The%20Weeknd%20-%20Blinding%20Lights%20%28Official%20Audio%29.mp3',
                'playing' => false
            ],
            [
                'artist' => 'Ed Sheeran',
                'title' => 'Photograph',
                'file_path' => 'https://ia600705.us.archive.org/21/items/EdSheeranPhotographOfficialMusicVideo/Ed%20Sheeran%20-%20Photograph%20%28Official%20Music%20Video%29.mp3',
                'playing' => false
            ],
            [
                'artist' => 'Adele',
                'title' => 'Rolling in the Deep',
                'file_path' => 'https://ia800909.us.archive.org/27/items/RollingInTheDeep_457/01RollingInTheDeep.mp3',
                'playing' => false
            ],
            [
                'artist' => 'Post Malone',
                'title' => 'Sunflower',
                'file_path' => 'https://ia601709.us.archive.org/8/items/post-malone-swae-lee-sunflower-spider-man-int-mp-3-128-k/Post%20Malone_%20Swae%20Lee%20-%20Sunflower%20%28Spider-Man_%20Int%28MP3_128K%29.mp3',
                'playing' => false
            ],
        ];

        foreach($songs as $song) {
            Song::create($song);
        }
    }
}
