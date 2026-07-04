<?php

namespace Tests\Feature\Auth;

test('public self-registration is disabled and points to the enquiry page', function () {
    $this->get('/register')
        ->assertRedirect(route('contact'))
        ->assertSessionHas('info');
});
