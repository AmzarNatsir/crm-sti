<?php

namespace App\Console\Commands;

use App\Models\District;
use App\Models\Province;
use App\Models\Regency;
use App\Models\Village;
use App\Services\RegionalApiService;
use Illuminate\Console\Command;

class SyncRegionalData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:regional-data {--only=all : all|provinces|regencies|districts|villages} {--provinces= : Comma separated province IDs to sync}';

    protected $description = 'Synchronize Indonesian regional data from API';

    public function handle(RegionalApiService $apiService)
    {
        $only = $this->option('only');
        $provincesOption = $this->option('provinces');
        $provinceIds = $provincesOption ? explode(',', $provincesOption) : [];

        // 1. Sync Provinces
        if ($only === 'all' || $only === 'provinces') {
            $this->info('Syncing Provinces...');
            $provinces = $apiService->getProvinces();
            
            $dataToInsert = [];
            foreach ($provinces as $province) {
                if (!empty($provinceIds) && !in_array($province['id'], $provinceIds)) {
                    continue;
                }
                $dataToInsert[] = [
                    'id' => $province['id'],
                    'name' => $province['name'],
                    // 'created_at' => now(), 'updated_at' => now() // If timestamps exist
                ];
            }
            
            if (!empty($dataToInsert)) {
                Province::upsert($dataToInsert, ['id'], ['name']);
            }
            $this->info('Provinces synced: ' . count($dataToInsert));
        }

        // 2. Sync Regencies
        if ($only === 'all' || $only === 'regencies') {
            $this->info('Syncing Regencies...');
            $provinces = Province::when(!empty($provinceIds), function($q) use ($provinceIds) {
                return $q->whereIn('id', $provinceIds);
            })->get();

            $bar = $this->output->createProgressBar($provinces->count());
            $bar->start();

            foreach ($provinces as $province) {
                $regencies = $apiService->getRegencies($province->id);
                $dataToInsert = [];
                foreach ($regencies as $regency) {
                    $dataToInsert[] = [
                        'id' => $regency['id'],
                        'province_id' => $province->id,
                        'name' => $regency['name']
                    ];
                }
                if (!empty($dataToInsert)) {
                    Regency::upsert($dataToInsert, ['id'], ['province_id', 'name']);
                }
                $bar->advance();
            }
            $bar->finish();
            $this->newLine();
        }

        // 3. Sync Districts
        if ($only === 'all' || $only === 'districts') {
            $this->info('Syncing Districts...');
            $regencies = Regency::when(!empty($provinceIds), function($q) use ($provinceIds) {
                return $q->whereIn('province_id', $provinceIds);
            })->get();

            $bar = $this->output->createProgressBar($regencies->count());
            $bar->start();

            foreach ($regencies as $regency) {
                $districts = $apiService->getDistricts($regency->id);
                $dataToInsert = [];
                foreach ($districts as $district) {
                    $dataToInsert[] = [
                        'id' => $district['id'],
                        'regency_id' => $regency->id,
                        'name' => $district['name']
                    ];
                }
                if (!empty($dataToInsert)) {
                    District::upsert($dataToInsert, ['id'], ['regency_id', 'name']);
                }
                $bar->advance();
            }
            $bar->finish();
            $this->newLine();
        }

        // 4. Sync Villages
        if ($only === 'all' || $only === 'villages') {
            $this->info('Syncing Villages...');
            // Filter districts based on provinceIds via regency relationship
            $districts = District::when(!empty($provinceIds), function($q) use ($provinceIds) {
                 return $q->whereHas('regency', function($q2) use ($provinceIds) {
                     $q2->whereIn('province_id', $provinceIds);
                 });
            })->get();
            
            $this->info('Found ' . $districts->count() . ' districts to sync villages for.');

            $bar = $this->output->createProgressBar($districts->count());
            $bar->start();
            
            foreach ($districts as $district) {
                $villages = $apiService->getVillages($district->id);
                $dataToInsert = [];
                foreach ($villages as $village) {
                    $dataToInsert[] = [
                        'id' => $village['id'],
                        'district_id' => $district->id,
                        'name' => $village['name']
                    ];
                }
                if (!empty($dataToInsert)) {
                    Village::upsert($dataToInsert, ['id'], ['district_id', 'name']);
                }
                $bar->advance();
            }
            $bar->finish();
            $this->newLine();
        }

        $this->info('Synchronization complete!');
    }
}
