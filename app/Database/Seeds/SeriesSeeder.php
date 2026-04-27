<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SeriesSeeder extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect();

        $seriesList = [
            [
                'title'    => 'Peacemaker',
                'type'     => 'series',
                'disabled' => 1,
                'target'   => ['s' => 1, 'e' => 4],
                'seasons'  => [8]
            ],
            [
                'title'    => 'Legion',
                'type'     => 'series',
                'disabled' => 1,
                'target'   => ['s' => 2, 'e' => 7],
                'seasons'  => [8, 11, 8]
            ],
            [
                'title'    => 'Evil',
                'type'     => 'series',
                'disabled' => 1,
                'target'   => ['s' => 3, 'e' => 10],
                'seasons'  => [13, 13, 10, 14]
            ],
            [
                'title'    => 'From',
                'type'     => 'series',
                'disabled' => 1,
                'target'   => ['s' => 3, 'e' => 7],
                'seasons'  => [10, 10, 10]
            ],
            [
                'title'    => 'The Lord of the Rings: The Rings of Power',
                'type'     => 'series',
                'disabled' => 1,
                'target'   => ['s' => 2, 'e' => 8],
                'seasons'  => [8, 8]
            ],
            [
                'title'    => 'How to Get Away with Murder',
                'type'     => 'series',
                'disabled' => 1,
                'target'   => ['s' => 5, 'e' => 15],
                'seasons'  => [15, 15, 15, 15, 15, 15]
            ],
            [
                'title'    => 'See',
                'type'     => 'series',
                'disabled' => 1,
                'target'   => null,
                'seasons'  => [8, 8, 8]
            ],
            [
                'title'    => 'Money Heist: Korea - Joint Economic Area',
                'type'     => 'series',
                'disabled' => 1,
                'target'   => null,
                'seasons'  => [12]
            ],
            [
                'title'    => 'The Boys',
                'type'     => 'series',
                'disabled' => 0,
                'target'   => ['s' => 5, 'e' => 3],
                'seasons'  => [8, 8, 8, 8, 8]
            ],
            [
                'title'    => 'Monarch: Legacy of Monsters',
                'type'     => 'series',
                'disabled' => 0,
                'target'   => ['s' => 1, 'e' => 10],
                'seasons'  => [10]
            ],
        ];

        foreach ($seriesList as $series) {
            // 1. Insert Series
            $db->table('series_tracker')->insert([
                'title'      => $series['title'],
                'type'       => $series['type'],
                'disabled'   => $series['disabled'],
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ]);
            $seriesId = $db->insertID();

            // 2. Insert Seasons
            foreach ($series['seasons'] as $index => $totalEps) {
                $seasonNum = $index + 1;
                $db->table('series_seasons')->insert([
                    'series_id'  => $seriesId,
                    'season_num' => $seasonNum,
                    'total_eps'  => $totalEps,
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                $seasonId = $db->insertID();

                $episodes = [];
                // 3. Generate Episodes based on target progress
                for ($ep = 1; $ep <= $totalEps; $ep++) {
                    $status = 0; // 0 = none
                    $watchedAt = null;

                    if ($series['target']) {
                        $ts = $series['target']['s'];
                        $te = $series['target']['e'];

                        if ($seasonNum < $ts || ($seasonNum === $ts && $ep < $te)) {
                            $status = 2; // Done (Past episodes)
                            $watchedAt = date('Y-m-d H:i:s');
                        } elseif ($seasonNum === $ts && $ep === $te) {
                            $status = 1; // Watching (Current target episode)
                        }
                    }

                    $episodes[] = [
                        'season_id'  => $seasonId,
                        'ep_num'     => $ep,
                        'status'     => $status,
                        'watched_at' => $watchedAt,
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),
                    ];
                }
                
                // Batch insert all episodes for the current season
                if (!empty($episodes)) {
                    $db->table('series_episodes')->insertBatch($episodes);
                }
            }
        }
    }
}