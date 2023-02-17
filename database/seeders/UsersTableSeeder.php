<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Store;
use App\Models\UserStore;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use App\Models\Utility;
use App\Models\BlogSocial;
use App\Models\StoreThemeSettings;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = User::create(
            [
                'name' => 'Owner',
                'email' => 'owner@example.com',
                'password' => Hash::make('1234'),
                'type' => 'Owner',
                'lang' => 'en',
                'created_by' => 0,
            ]
        );

        $objStore = Store::create(
            [
                'name' => 'My Store',
                'email' => 'owner@example.com',
                'enable_storelink' => 'on',
                'store_theme' => 'yellow-style.css',
                'theme_dir' => 'theme1',
                'whatsapp' => '#',
                'facebook' => '#',
                'instagram' => '#',
                'twitter' => '#',
                'youtube' => '#',
                'footer_note' => '© 2020 LMSGo. All rights reserved',
                'enable_header_img' => 'on',
                'header_img' => 'img-15.jpg',
                'header_title' => 'Home Accessories',
                'header_desc' => 'There is only that moment and the incredible certainty that everything under the sun has been written by one hand only.',
                'button_text' => 'Start shopping',
                'enable_rating' => 'on',
                'enable_subscriber' => 'on',
                'subscriber_title' => 'Always on time',
                'sub_title' => 'Subscription here',
                'logo' => 'logo.png',
                'created_by' => $admin->id,
                'header_name' => 'Course Certificate',
                'certificate_template' => 'template1',
                'certificate_color' => 'b10d0d',
                'certificate_gradiant' => 'color-one',
            ]
        );

        $student = \App\Models\Student::create(
            [
                'name' => 'Student',
                'email' => 'student@example.com',
                'password' => Hash::make('1234'),
                'phone_number' => '9876543210',
                'store_id' => $objStore->id,
                'avatar' => 'avatar.png',
                'lang' => 'en',
            ]
        );

        $admin->current_store = $objStore->id;
        $admin->save();

        UserStore::create(
            [
                'user_id' => $admin->id,
                'store_id' => $objStore->id,
                'permission' => 'Owner',
            ]
        );
        \App\Models\BlogSocial::create(
            [
                'enable_social_button' => 'on',
                'enable_email' => 'on',
                'enable_twitter' => 'on',
                'enable_facebook' => 'on',
                'enable_googleplus' => 'on',
                'enable_linkedIn' => 'on',
                'enable_pinterest' => 'on',
                'enable_stumbleupon' => 'on',
                'enable_whatsapp' => 'on',
                'store_id' => $objStore->id,
                'created_by' => $admin->id,
            ]
        );

        // Utility::add_landing_page_data();
    }
}
