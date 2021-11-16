<?php

namespace VCComponent\Laravel\Vicoders\Core\Test\Feature\s3;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class FileControllerTest extends TestCase
{
    /** @test */
    public function can_upload_file_to_configed_drive_by_admin() {
        $this->withoutMiddleware(['jwt.auth']);

        $upload_file_type = $this->app['config']->get('filesystems.default');

        Storage::fake($upload_file_type);

        $file = UploadedFile::fake()->create('image.jpg');
 
        $files = [
            'file' => $file,
            'upload_path' => 'upload'
        ];
 
        $response = $this->call('POST', 'api/file/upload', $files);
        
        $response->assertStatus(200);
        $response->assertJson([
            "success" => true
        ]);
    }

    /** @test */
    public function should_not_upload_file_to_configed_storage_without_file() {
        $this->withoutMiddleware(['jwt.auth']);

        $upload_file_type = $this->app['config']->get('filesystems.default');

        Storage::fake($upload_file_type);
 
        $files = [
            'upload_path' => 'upload'
        ];
 
        $response = $this->call('POST', 'api/file/upload', $files);
        
        $response->assertStatus(422);
        $response->assertJson([
            "message" => "The given data was invalid."
        ]);
    }

    /** @test */
    public function can_upload_file_to_configed_storage_without_upload_path() {
        $this->withoutMiddleware(['jwt.auth']);

        $upload_file_type = $this->app['config']->get('filesystems.default');

        Storage::fake($upload_file_type);

        $file = UploadedFile::fake()->create('image.jpg');
 
        $files = [
            'file' => $file,
        ];
 
        $response = $this->call('POST', 'api/file/upload', $files);
        
        $response->assertStatus(422);
        $response->assertJson([
            "message" => "The given data was invalid."
        ]);
    }
}