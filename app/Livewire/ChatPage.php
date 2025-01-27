<?php

namespace App\Livewire;

use Livewire\Attributes\Computed;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Title('سیستم چت reverb')]
class ChatPage extends Component
{
    /**
     * Get the system messages.
     *
     * @return array
     */
    #[Computed]
    public function systemMessages(): array
    {
        return [
            'سلااام 👋',
            'به چت ساده خیلی خوش اومدید 🔥',
            'توی این محیط قراره که به صورت ناشناس چت کنیم 🍃',
            'لطفا برای حفظ امنیت و حریم خصوصی خودت و دیگران از اشتراک‌گذاری هرگونه اطلاعات حساس و شخصی خودداری کن ❤️',
            'امیدوارم از بودن توی این محیط لذت کافی رو ببری ✨',
            'راستی میتونی با ارسال کلمه "دارک"؛ تم رو به حالت دارک و با ارسال کلمه "لایت"؛ تم رو به حالت لایت تغییر بدی 🌚',
        ];
    }
}
