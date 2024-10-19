<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class EmojiTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('emoji')->insert([
            [
                'name' => 'Cat',
                'avatar' => 'AdobeStock_565412797_Preview.jpeg',
                'profile' => 'AdobeStock_565412797_Preview.jpeg',
                'micro' => 'AdobeStock_565412797_Preview.jpeg',
            ],
            [
                'name' => 'Lion',
                'avatar' => 'AdobeStock_701831584_Preview.jpeg',
                'profile' => 'AdobeStock_701831584_Preview.jpeg',
                'micro' => 'AdobeStock_701831584_Preview.jpeg',
            ],
            [
                'name' => 'Rabbit',
                'avatar' => 'AdobeStock_804961323_Preview.jpeg',
                'profile' => 'AdobeStock_804961323_Preview.jpeg',
                'micro' => 'AdobeStock_804961323_Preview.jpeg',
            ],
            [
                'name' => 'Snake',
                'avatar' => 'AdobeStock_807622076_Preview.png',
                'profile' => 'AdobeStock_807622076_Preview.png',
                'micro' => 'AdobeStock_807622076_Preview.png',
            ],
            [
                'name' => 'Scarf',
                'avatar' => 'AdobeStock_845253103_Preview.jpeg',
                'profile' => 'AdobeStock_845253103_Preview.jpeg',
                'micro' => 'AdobeStock_845253103_Preview.jpeg',
            ],
            [
                'name' => 'Cat 2',
                'avatar' => 'AdobeStock_880990847_Preview.png',
                'profile' => 'AdobeStock_880990847_Preview.png',
                'micro' => 'AdobeStock_880990847_Preview.png',
            ],
            [
                'name' => 'Dog',
                'avatar' => 'AdobeStock_928618242_Preview.jpeg',
                'profile' => 'AdobeStock_928618242_Preview.jpeg',
                'micro' => 'AdobeStock_928618242_Preview.jpeg',
            ],
        ]);
    }
}
