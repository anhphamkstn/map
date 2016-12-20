<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(App\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'role_id' => 3,
        'active' => true,
        'phone_1' => $faker->phoneNumber,
        'phone_2' => $faker->phoneNumber,
        'password' => bcrypt(str_random(10))
    ];
});

$factory->define(App\Lead::class, function (Faker\Generator $faker) {
    return [
        'lead_source' => $faker->company,
        'lead_source_description' => $faker->text,
        'status' => "Opportune",
        'status_description' => $faker->text,
        'reference_source_by' => $faker->text,
        'name_prefix' => $faker->title,
        'name_first_name' => $faker->firstName,
        'name_last_name' => $faker->lastName,
        'position' => $faker->jobTitle,
        'account_name' => $faker->company,
        'title' => $faker->sentence,
        'department' => $faker->company,
        'website' => $faker->url,
        'office_phone' => $faker->phoneNumber,
        'mobile_phone' => $faker->phoneNumber,
        'home_phone' => $faker->phoneNumber,
        'other_phone' => $faker->phoneNumber,
        'fax_number' => $faker->phoneNumber,
        'email' => $faker->email,
        'other_email' => $faker->companyEmail,
        'do_not_call' => rand(0, 1) == 1,
        'sms_opt_in' => rand(0, 1) == 1,
        'email_opt_out' => rand(0, 1) == 1,
        'invoice_email' => rand(0, 1) == 1
    ];
});

$factory->define(App\Address::class, function (Faker\Generator $faker) {
    return [
        'primary' => true,
        'zip_code' => $faker->postcode,
        'district' => $faker->streetAddress,
        'state_province' => $faker->streetAddress,
        'country' => $faker->country,
        'street' => $faker->streetAddress,
        'lead_id' => App\Lead::orderByRaw("RANDOM()")->first()->id,
        'lon' => $faker->longitude($min = 76, $max = 78),
        'lat' => $faker->latitude($min = 28, $max = 29),
    ];
});

$factory->define(App\Task::class, function (Faker\Generator $faker) {
    return [
        'assign_type' => 'Appointment',
        'assigned_to' => App\User::orderByRaw("RANDOM()")->first()->id,
        'priority' => rand(1, 3),
        'status' => 'Created',
        'regarding' => 'I want to have one extra cookie',
        'due_time_from' => $faker->dateTimeBetween($startDate = 'now', $endDate = '+ 5 days', $timezone = date_default_timezone_get()),
        'due_time_to' => $faker->dateTimeBetween($startDate = '5 days', $endDate = '10 days', $timezone = date_default_timezone_get()),
        'lead_id' => App\Lead::orderByRaw("RANDOM()")->first()->id
    ];
});

$factory->define(App\Organization::class, function (Faker\Generator $faker) {
    $billing_address = [
        'zip_code' => $faker->postcode,
        'district' => $faker->streetAddress,
        'state_province' => $faker->streetAddress,
        'country' => $faker->country,
        'street' => $faker->streetAddress,
        'lon' => $faker->randomFloat(4, 76, 78),
        'lat' => $faker->randomFloat(4, 28, 29)
    ];

    $shipping_address = [
        'zip_code' => $faker->postcode,
        'district' => $faker->streetAddress,
        'state_province' => $faker->streetAddress,
        'country' => $faker->country,
        'street' => $faker->streetAddress,
        'lon' => $faker->randomFloat(4, 76, 78),
        'lat' => $faker->randomFloat(4, 28, 29)
    ];

    return [
        'org_source' => array('Converted from lead', 'Created by salesperson on mobile app', 'Created by admin') [rand(0, 2)],
        'status' => array('New', 'Approved', 'Close org') [rand(0, 2)],
        'reference_source_by' => array('Employee', 'Partner', 'Website', 'Current customer') [rand(0, 3)],
        'name' => $faker->company,
        'org_parent' => $faker->company,
        'industry_zone' => $faker->word,
        'number_of_employees' => rand(5, 1000),
        'annual_revenue' => rand(100000, 1000000),
        'office_phone' => $faker->phoneNumber,
        'fax_number' => $faker->phoneNumber,
        'email' => $faker->companyEmail,
        'other_email' => $faker->email,
        'do_not_call' => rand(0, 1) == 0,
        'email_opt_out' => rand(0, 1) == 0,
        'invoice_email' => rand(0, 1) == 0,
        'billing_address' => json_encode($billing_address),
        'shipping_address' => json_encode($shipping_address)
    ];
});

$factory->define(App\Contact::class, function (Faker\Generator $faker) {
    $address = [[
        'zip_code' => $faker->postcode,
        'district' => $faker->streetAddress,
        'state_province' => $faker->streetAddress,
        'country' => $faker->country,
        'street' => $faker->streetAddress,
        'lon' => $faker->randomFloat(4, 76, 78),
        'lat' => $faker->randomFloat(4, 28, 29)
    ]];

    return [
        'contact_source' => array('Converted from lead', 'Created by salesperson on mobile app', 'Created by admin') [rand(0, 2)],
        'status' => array('New', 'Approved', 'Close org') [rand(0, 2)],
        'reference_source_by' => array('Employee', 'Partner', 'Website', 'Current customer') [rand(0, 3)],
        'org_id' => App\Organization::orderByRaw("RANDOM()")->first()->id,
        'prefix_name' => array('Mr.', 'Ms.')[rand(0, 1)],
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'birthday' => $faker->date(),
        'position' => $faker->jobTitle,
        'department' => $faker->company,
        'report_to' => $faker->company,
        'office_phone' => $faker->phoneNumber,
        'mobile_phone' => $faker->phoneNumber,
        'home_phone' => $faker->phoneNumber,
        'other_phone' => $faker->phoneNumber,
        'fax_number' => $faker->phoneNumber,
        'email' => $faker->companyEmail,
        'other_email' => $faker->email,

        'do_not_call' => rand(0, 1) == 1,
        'sms_opt_in' => rand(0, 1) == 1,
        'email_opt_out' => rand(0, 1) == 1,
        'invoice_email' => rand(0, 1) == 1,

        'address' => json_encode($address)
    ];
});

$factory->define(App\Remark::class, function (Faker\Generator $faker) {
    return [
        'task_id' => App\Task::orderByRaw("RANDOM()")->first()->id,
        'status' => 0,
        'note' => $faker->text,
        'created_by' => App\User::orderByRaw("RANDOM()")->first()->id,
        'modified_by' => App\User::orderByRaw("RANDOM()")->first()->id,
    ];
});

$factory->define(App\TrackingLocation::class, function (Faker\Generator $faker) {
    return [
        'user_id' => App\User::orderByRaw("RANDOM()")->first()->id,
        'lon' => $faker->longitude($min = 76, $max = 78),
        'lat' => $faker->latitude($min = 28, $max = 29),
        'note' => $faker->text
    ];
});

$factory->define(App\Attendance::class, function (Faker\Generator $faker) {
    $task = App\Task::orderByRaw("RANDOM()")->first();

    return [
        'task_id' => $task->id,
        'lon' => $faker->longitude($min = 76, $max = 78),
        'lat' => $faker->latitude($min = 28, $max = 29),
        'note' => $faker->text,
        'type' => rand(0, 1) == 0 ? 'checkin' : 'justify',
        'created_at' => $faker->dateTimeBetween($startDate = '-2 days', $endDate = 'now', $timezone = date_default_timezone_get())
    ];
});

$factory->define(App\Product::class, function (Faker\Generator $faker) {
    return [
        'source' => "seeder",
        'code' => $faker->text(10),
        'serial_number' => $faker->text(10),
        'active' => rand(0, 1) == 1,
        'barcode' => $faker->text(10),
        'barcode_image' => 1,
        'qr_string' => $faker->text(10),
        'qr_image' => 1,
        'name' => $faker->name,
        'other_name' => $faker->name,
        'price' => $faker->numberBetween(400, 3000),
        'sell_unit' => 'set',
        'category' => $faker->word,
        'group' => $faker->word,
        'class' => $faker->word,
        'type' => $faker->word,
        'exp_time' => $faker->dateTimeBetween('now', '+1 years'),
        'manu_name' => $faker->name,
        'manu_phone' => $faker->phoneNumber,
        'manu_fax' => $faker->phoneNumber,
        'manu_address' => $faker->address,
        'manu_website' => $faker->domainName,
        'manu_fanpage' => 'https://www.facebook.com/' . $faker->domainWord,
        'vendor_name' => $faker->name,
        'vendor_code' => $faker->text(10),
        'vendor_phone' => $faker->phoneNumber,
        'vendor_fax' => $faker->phoneNumber,
        'vendor_address' => $faker->address,
        'vendor_website' => $faker->domainName,
        'vendor_fanpage' => 'https://www.facebook.com/' . $faker->domainWord,
    ];
});

$factory->define(App\StockDetail::class, function (Faker\Generator $faker) {
    return [
        'stock_id' => App\StockHeader::orderByRaw("RANDOM()")->first()->id,
        'product_id' => App\Product::orderByRaw("RANDOM()")->first()->id,
        'usage_unit' => "UNIT",
        'stock_min' => 10,
        'stock_max' => 90,
        'quantity_per_unit' => $faker->numberBetween(400, 3000),
        'quantity_in_stock' => $faker->numberBetween(400, 3000)
    ];
});

$factory->define(App\StockHeader::class, function (Faker\Generator $faker) {
    return [
        'stock_code' => $faker->text(20),
        'stock_name' => $faker->name,
        'address' => $faker->address
    ];
});

$factory->define(App\ProductImage::class, function (Faker\Generator $faker) {
    return [
        'product_id' => App\Product::orderByRaw("RANDOM()")->first()->id,
        'file_name' => $faker->name,
        'order' => 0,
        'url' => $faker->url
    ];
});

$factory->define(App\Opportune::class, function (Faker\Generator $faker) {
    return [
        'opp_source' => 'Website',
        'status' => 'Created',
        'opp_title' => $faker->title,
        'forecast_amount' => doubleval($faker->numberBetween(999, 99999) / 100),
        'opp_type' => 'Type 1',
        'amount' => doubleval($faker->numberBetween(999, 99999) / 100),
        'expect_close_date' => $faker->dateTimeBetween('now', '+ 30 days'),
        'probability_percent' => $faker->numberBetween(1, 100),
        'next_step' => $faker->text(100),
        'contact_id' => App\Contact::orderByRaw("RANDOM()")->first()->id,
        'org_id' => App\Organization::orderByRaw("RANDOM()")->first()->id,
    ];
});

$factory->define(App\Order::class, function (Faker\Generator $faker) {
    $billing_address = [
        'zip_code' => $faker->postcode,
        'district' => $faker->streetAddress,
        'state_province' => $faker->streetAddress,
        'country' => $faker->country,
        'street' => $faker->streetAddress,
        'lon' => $faker->randomFloat(4, 76, 78),
        'lat' => $faker->randomFloat(4, 28, 29)
    ];

    $shipping_address = [
        'zip_code' => $faker->postcode,
        'district' => $faker->streetAddress,
        'state_province' => $faker->streetAddress,
        'country' => $faker->country,
        'street' => $faker->streetAddress,
        'lon' => $faker->randomFloat(4, 76, 78),
        'lat' => $faker->randomFloat(4, 28, 29)
    ];

    do {
        $code = 'EN' . rand(1000000, 9999999);
        $order_code = App\Order::where('order_code', $code)->first();
    } while (!empty($order_code));

    return [
        'created_by' => App\User::orderByRaw("RANDOM()")->first()->id,
        'order_code' => $code,
        'order_date' => $faker->dateTimeBetween('now', '+ 2 months'),
        'status' => array('pending', 'done')[rand(0, 1)],
        'contact_id' => App\Contact::orderByRaw("RANDOM()")->first()->id,
        'shipping_address' => json_encode($shipping_address),
        'billing_address' => json_encode($billing_address),
        'stock_id' => App\StockHeader::orderByRaw("RANDOM()")->first()->id,
        'order_total' => $faker->randomFloat(2, 1000, 2000),
        'order_total_vat' => $faker->randomFloat(2, 2000, 2200),
        'order_discounted_percent' => $faker->numberBetween(0, 30),
        'order_discounted_amount' => $faker->randomFloat(2, 30, 220),
        'order_gross_payment' => $faker->randomFloat(2, 1000, 1200),
        'order_payment_type' => array('Cash', 'ATM', 'Bank Transfer')[rand(0, 2)]
    ];
});

$factory->define(App\OrderProduct::class, function (Faker\Generator $faker) {
    return [
        'order_id' => App\Order::orderByRaw("RANDOM()")->first()->id,
        'product_id' => App\Product::orderByRaw("RANDOM()")->first()->id,
        'price' => $faker->randomFloat(2, 1000, 2000),
        'quantity' => $faker->numberBetween(1, 10),
        'vat' => $faker->randomFloat(2, 20, 30),
        'man_off_percent' => $faker->numberBetween(0, 30),
        'amount' => $faker->randomFloat(2, 30, 220)
    ];
});

$factory->define(App\CustomGeocoding::class, function (Faker\Generator $faker) {
    return [
        "tag_name" => $faker->name,
        "country" => $faker->country,
        "state" => "",
        "city" => $faker->city,
        "area" => $faker->streetName,
        "postal_code" => $faker->postcode,
        "building_name" => $faker->streetName,
        'lon' => $faker->randomFloat(4, 76, 78),
        'lat' => $faker->randomFloat(4, 28, 29)
    ];
});