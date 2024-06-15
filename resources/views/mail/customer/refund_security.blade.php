    @extends('layouts.email')

    @section('title', '')

    @section('greet', "Hello, $user->name")

    @section('message')
        <p style="font-family: 'Inter', sans-serif;color:#1B1B1B;">Thank you for chering on Chere!</p>
        <p style="font-family: 'Inter', sans-serif;color:#1B1B1B;">This email is to confirm that your rental return has been
            registered with the owner.</p>
        <p style="font-family: 'Inter', sans-serif;color:#1B1B1B;">If you purchased a security deposit, we will be returning
            it to you shortly.</p>
    @endsection

    @section('wishes', 'With love,')
