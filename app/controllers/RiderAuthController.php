<?php
class RiderAuthController extends Controller {
    public function login(): void {
        view('auth/rider_login');
    }
}
