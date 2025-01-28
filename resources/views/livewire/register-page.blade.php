<div class="relative grid w-full p-4 h-svh lg:grid-cols-2 dark:bg-neutral-950">
    <section class="flex flex-col items-center justify-center gap-8">

        <header class="relative flex flex-col items-center gap-2">

            <p class="absolute -mt-16 text-6xl font-black text-white dark:text-secondary-900/80 ">
              سیستم ساده چت
            </p>

            <h1 class="mt-4 mb-4 text-lg font-bold text-white dark:text-secondary-300">
                چت - لذت گفت‌وگوی ناشناس
            </h1>
            <p class="text-sm text-white dark:text-secondary-400">
                جهت حفظ حریم خصوصی؛ شما به عنوان یک کاربر ناشناس وارد خواهید شد
            </p>
        </header>


        <main class="w-full max-w-sm">
            <form wire:submit.prevent="submit" class="flex flex-col w-full gap-4">
                <div class="flex flex-col w-full gap-2">
                    <div class="flex items-center justify-between w-full">
                        <label for="input-okkio-name" class="w-full text-white dark:text-secondary-300">
                            نام نمایشی
                            <span class="text-rose-500">*</span>
                        </label>

                        <div x-data="{ show: false }" @mouseEnter="show = true" @mouseLeave="show = false" class="relative flex items-center justify-center cursor-pointer">
                            <svg
                                class="w-4 h-4 transition-all duration-500 hover:text-primary-600"
                                :class="show ? 'text-primary-600' : 'text-secondary-400'"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.879 7.519c1.171-1.025 3.071-1.025 4.242 0 1.172 1.025 1.172 2.687 0 3.712-.203.179-.43.326-.67.442-.745.361-1.45.999-1.45 1.827v.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 5.25h.008v.008H12v-.008Z" />
                            </svg>
                            <div x-cloak x-show="show" x-transition class="absolute z-20 w-64 max-w-sm px-4 py-2 text-sm text-justify text-white bg-red-500 rounded-md -top-20">
                                از اشتراک گذاری اطلاعات تخمی و خطرناک مانند شماره موبایل در این قسمت کاملا خودداری نمایید.
                            </div>
                        </div>
                    </div>
                    <input
                        wire:model="display_name"
                        id="input-okkio-name"
                        type="text"
                        placeholder="نام کاربری خود را وارد کنید"
                        class="w-full px-4 py-2 transition-all duration-500 rounded outline-none ring-1 ring-secondary-100 focus:ring-primary-500 dark:bg-secondary-900 dark:ring-secondary-800 dark:placeholder:text-secondary-500 dark:text-secondary-300"
                    >
                    @error('display_name')
                    <p class="text-sm text-rose-500">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <button type="submit" class="w-full px-4 py-2 text-white transition-all duration-500 bg-blue-600 rounded-md hover:bg-primary-500 dark:bg-primary-700 dark:hover:bg-primary-600">
                        ورود به صفحه چت
                    </button>
                </div>
            </form>
        </main>

        <footer>
            <p class="text-sm text-white dark:text-secondary-400">
                با
                <span class="animate-pulse">
                    سختی در
                </span>
               زیر  زمین
            </p>
        </footer>

    </section>

    <aside class="relative items-center justify-center hidden overflow-hidden lg:flex">
        <div class="w-full h-full overflow-hidden rounded-lg">
            <img class="object-cover object-center w-full h-full"
                src="{{ \Illuminate\Support\Facades\Vite::asset('resources/images/covers/live-chat.png') }}" alt="" >
        </div>
    </aside>
</div>
