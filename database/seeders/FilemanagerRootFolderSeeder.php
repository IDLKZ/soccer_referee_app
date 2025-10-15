<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use LivewireFilemanager\Filemanager\Models\Folder;
use App\Models\User;

class FilemanagerRootFolderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create root folder if it doesn't exist
        $rootFolder = Folder::where('slug', 'root')->first();
        if (!$rootFolder) {
            $rootFolder = Folder::create([
                'name' => 'Root',
                'slug' => 'root',
                'parent_id' => null,
                'user_id' => User::first()?->id ?? 1,
            ]);

            $this->command->info('Root folder created with ID: ' . $rootFolder->id);
        } else {
            $this->command->info('Root folder already exists with ID: ' . $rootFolder->id);
        }

        // Create a child folder (Media) for filemanager to use
        $mediaFolder = Folder::where('parent_id', $rootFolder->id)->first();
        if (!$mediaFolder) {
            $mediaFolder = Folder::create([
                'name' => 'Media',
                'slug' => 'media',
                'parent_id' => $rootFolder->id,
                'user_id' => User::first()?->id ?? 1,
            ]);

            $this->command->info('Media folder created with ID: ' . $mediaFolder->id);
        } else {
            $this->command->info('Media folder already exists with ID: ' . $mediaFolder->id);
        }
    }
}