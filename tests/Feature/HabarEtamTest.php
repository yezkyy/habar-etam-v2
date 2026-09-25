<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\JobVacancy;
use App\Models\Business;
use App\Models\CulinaryPlace;
use App\Models\Event;
use App\Models\Community;
use App\Models\QuickSale;
use App\Models\Report;
use App\Models\Verification;
use App\Services\NikVerificationService;

class HabarEtamTest extends TestCase
{
    public function test_public_home_and_module_catalog_pages_render_successfully()
    {
        $response = $this->get('/');
        $response->assertStatus(200);
        $response->assertSee('Habar Etam');

        $response = $this->get('/bursa-kerja');
        $response->assertStatus(200);

        $response = $this->get('/produk-jasa');
        $response->assertStatus(200);

        $response = $this->get('/kuliner');
        $response->assertStatus(200);

        $response = $this->get('/event');
        $response->assertStatus(200);

        $response = $this->get('/komunitas');
        $response->assertStatus(200);

        $response = $this->get('/jual-cepat');
        $response->assertStatus(200);

        $response = $this->get('/lapor-etam');
        $response->assertStatus(200);

        $response = $this->get('/smart-city/kontak-darurat');
        $response->assertStatus(200);

        $response = $this->get('/smart-city/harga-pangan');
        $response->assertStatus(200);

        $response = $this->get('/smart-city/lingkungan');
        $response->assertStatus(200);

        $response = $this->get('/smart-city/budaya');
        $response->assertStatus(200);

        $response = $this->get('/sitemap.xml');
        $response->assertStatus(200);
        $response->assertHeader('Content-Type', 'text/xml; charset=UTF-8');
    }

    public function test_nik_verification_service_validates_kukar_format()
    {
        $service = new NikVerificationService();

        // Valid Kukar NIK (Male born 15-05-1990 in Tenggarong 640206)
        $validNik = '6402061505900001';
        $result = $service->analyze($validNik, '1990-05-15');
        $this->assertTrue($result['is_valid_format']);
        $this->assertTrue($result['is_kukar']);
        $this->assertTrue($result['birth_date_valid']);
        $this->assertEquals('Tenggarong', $result['district_name']);

        // Invalid prefix (non-Kukar)
        $invalidPrefix = '3201011505900001';
        $resultInvalid = $service->analyze($invalidPrefix, '1990-05-15');
        $this->assertFalse($resultInvalid['is_kukar']);

        // DOB mismatch
        $resultDobMismatch = $service->analyze($validNik, '1995-10-20');
        $this->assertFalse($resultDobMismatch['birth_date_valid']);
    }

    public function test_user_can_register_with_kukar_nik()
    {
        $uniqueSuffix = str_pad(rand(100, 9999), 4, '0', STR_PAD_LEFT);
        $email = 'testwarga_' . time() . '_' . rand(100, 999) . '@warga.id';
        $nik = '640206150590' . $uniqueSuffix;

        $response = $this->post('/daftar', [
            'name' => 'Warga Uji Coba',
            'email' => $email,
            'phone' => '08129988' . rand(1000, 9999),
            'nik' => $nik,
            'date_of_birth' => '1990-05-15',
            'district' => 'Tenggarong',
            'address' => 'Jl. Danau Aji No. 10',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'terms' => '1',
        ]);

        $response->assertRedirect('/member/profil');
        $this->assertDatabaseHas('users', ['email' => $email]);
    }

    public function test_admin_can_access_cms_and_member_cannot()
    {
        $admin = User::where('role', 'admin')->first();
        $member = User::where('role', 'member')->first();

        // Guest cannot access admin
        $this->get('/admin')->assertRedirect('/masuk');

        // Member cannot access admin
        $this->actingAs($member)->get('/admin')->assertRedirect('/masuk');

        // Admin can access admin dashboard & modules
        $this->actingAs($admin)->get('/admin')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/warga')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/verifikasi')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/moderasi')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/lapor-etam')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/redaksi/feed')->assertStatus(200);
        $this->actingAs($admin)->get('/admin/export')->assertStatus(200);
    }

    public function test_verified_member_can_submit_report()
    {
        $member = User::where('role', 'member')->where('status', 'verified')->first();

        $response = $this->actingAs($member)->post('/lapor-etam', [
            'category' => 'infrastruktur',
            'title' => 'Jalan Berlubang di Dekat Jembatan Kutai',
            'description' => 'Lubang sedalam 15cm membahayakan pengendara roda dua di malam hari di jalan akses jembatan.',
            'location_district' => 'Tenggarong',
            'address' => 'Jl. Wolter Monginsidi RT 05',
            'latitude' => -0.4439,
            'longitude' => 116.9856,
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('reports', [
            'user_id' => $member->id,
            'title' => 'Jalan Berlubang di Dekat Jembatan Kutai',
            'status' => 'pending_verification',
        ]);
    }

    public function test_admin_can_download_csv_export()
    {
        $admin = User::where('role', 'admin')->first();

        $response = $this->actingAs($admin)->get('/admin/export/reports');
        $response->assertStatus(200);
        $this->assertTrue(str_contains($response->headers->get('content-disposition'), 'attachment'));
    }
}
