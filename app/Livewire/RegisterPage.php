<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Number;
use Illuminate\Support\Str;
use Livewire\Attributes\Rule;
use Livewire\Attributes\Title;
use Livewire\Component;
use Throwable;

#[Title('ورود به چت')]
class RegisterPage extends Component
{


    #[Rule(['required', 'min:3', 'max:20', 'string'])]
    public string $display_name = '';


    public function submit(): void
    {
        $this->validate();

        $address = md5(Str::random(8) . '-' . md5($this->display_name) . '-' . time());
        // dd("$address@dick.chat");

       // $exists = User::whereName(trim($this->display_name))->first();

        $user = User::create([
            // 'name' => trim(trim($this->display_name).' '.$exists ? $this->randomId() : ''),
            'name' => trim($this->display_name).''.$this->randomId(),
            'email' => "$address@dick.chat"
        ]);

        auth()->login($user);
        // navigate: true do like
        // spa single page application
        $this->redirectRoute('chatHome', navigate: true);
    }

    private function randomId(): int
    {
        try {
            return random_int(10000, 99999);
        } catch (\Throwable) {
            return $this->randomId();
        }
    }

}
