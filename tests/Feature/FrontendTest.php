<?php

use App\Models\Enquiry;

test('the website pages load', function (string $url) {
    $this->get($url)->assertOk();
})->with(['/', '/about', '/courses', '/gallery', '/contact']);

test('a visitor can submit an admission enquiry', function () {
    $response = $this->post('/contact', [
        'name' => 'Ravi Kumar',
        'mobile' => '9876543210',
        'email' => 'ravi@example.com',
        'message' => 'BCA course ke baare me jaankari chahiye.',
    ]);

    $response->assertRedirect()->assertSessionHas('success');

    expect(Enquiry::where('mobile', '9876543210')->exists())->toBeTrue();
});

test('an enquiry requires name and mobile', function () {
    $this->from('/contact')
        ->post('/contact', ['name' => '', 'mobile' => ''])
        ->assertRedirect('/contact')
        ->assertSessionHasErrors(['name', 'mobile']);
});

test('the login page loads with portal branding', function () {
    $this->get('/login')
        ->assertOk()
        ->assertSee('Portal Login');
});
