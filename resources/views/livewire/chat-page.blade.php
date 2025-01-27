<div class="w-full h-svh grid grid-cols-12 p-4 bg-gray-100 gap-4 dark:bg-secondary-950">
    <aside class="col-span-4 flex flex-col gap-4">
        <div class="bg-white p-4 ring-1 ring-secondary-200 rounded-md dark:bg-secondary-900 dark:ring-secondary-800">

            <div class="flex items-center gap-4">
                <div
                    class="w-10 h-10 min-w-10 min-h-10 bg-secondary-200 dark:bg-secondary-800 rounded-md overflow-hidden">
                    {{--                    <img src="{{ auth()->user()->avatar }}" alt="" class="w-full h-full object-center object-cover">--}}
                    <img src="{{ asset('images/profile_user.png') }}" alt=""
                         class="w-full h-full object-center object-cover">
                </div>
                <div class="text-sm flex flex-col gap-1 truncate">
                    <p class="text-secondary-700 dark:text-secondary-300 truncate">
                        {{ auth()->user()->name }}
                    </p>
                    <button class="text-secondary-500 text-xs dark:text-secondary-400 truncate">
                        {{ str(auth()->user()->email)->replace('@okkio.chat', '')->toString() }}
                    </button>
                </div>
            </div>

        </div>
        <div
            class="flex-auto h-0 bg-white ring-1 ring-secondary-200 rounded-md flex flex-col dark:bg-secondary-900 dark:ring-secondary-800">
            <div class="px-4 py-4">
                <p class="text-primary-600 dark:text-primary-500">
                    کاربران آنلاین
                </p>
            </div>

            <ul id="online-users-list" class="flex flex-col gap-4 flex-auto h-0 scroll overflow-y-auto px-4 truncate">

            </ul>
        </div>
    </aside>

    <main class="col-span-8 flex flex-col items-center justify-between gap-4">
        <div
            id="chat-list-wrapper"
            class="flex-auto h-0 overflow-y-auto w-full scroll rounded-md ring-1 ring-secondary-200 w-full p-4 bg-white dark:bg-secondary-900 dark:ring-secondary-800">
            <ul class="flex flex-col gap-4" id="chat-list">
                @foreach($this->systemMessages() as $systemMessage)
                    <li class="flex items-start gap-4">
                        <div
                            class="min-w-10 min-h-10 w-10 h-10 bg-secondary-100 dark:bg-secondary-800 rounded-md overflow-hidden">
                            <img src="{{ asset('images/profile_user.png') }}" alt=""
                                 class="w-full h-full object-center object-cover">
                            {{-- <img src="{{ gravatar('okkio-system-20') }}" alt=""
                                  class="w-full h-full object-center object-cover">--}}
                        </div>
                        <div class="text-sm flex flex-col gap-1">
                            <div class="text-secondary-500 flex gap-4 dark:text-secondary-400">
                                <div class="flex gap-1 items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                         stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                              d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                    </svg>
                                    <p>
                                        سیستم چت
                                    </p>
                                </div>
                            </div>
                            <p class="text-secondary-700 message dark:text-secondary-300">
                                {{ $systemMessage }}
                            </p>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>

        <div
            class="rounded-md ring-1 ring-secondary-200 w-full p-4 bg-white dark:bg-secondary-900 dark:ring-secondary-800">
            <form id="form-message" class="flex w-full gap-4">
                <label for="input-message" class="grow">
                    <input
                        required
                        name="message"
                        type="text"
                        id="input-message"
                        class="w-full px-4 py-2 rounded-md outline-none bg-secondary-100
                        ring-1 ring-secondary-200 dark:bg-secondary-800 dark:ring-secondary-700
                        dark:placeholder:text-secondary-500 dark:text-secondary-300"
                        placeholder="پیام خود را بنویسید..."
                    >
                </label>
                <button id="btn-message" class="bg-blue-600 dark:bg-primary-800 px-4 py-2 rounded-md text-white">
                    ارسال پیام
                </button>
            </form>
        </div>
    </main>
</div>

@script
<script>
    const chatList = document.getElementById('chat-list');
    const usersList = document.getElementById('online-users-list');
    const chatListWrapper = document.getElementById('chat-list-wrapper');
    const inputMessage = document.getElementById('input-message');
    const messageForm = document.getElementById('form-message');
    const messageBtn = document.getElementById('btn-message');

    // Echo.join('chat_channel').here(users => {
    //     // here() method get all users joined to current channel
    //     console.log(users)
    // }).joining(user => {
    //     // for joining user to channel
    //     console.log(user)
    // })

    // defined variable
    // const chatList = document.getElementById('chat-list');

    //// for show user type something
    inputMessage.addEventListener('keyup', handleMessageTypingEvent)
    messageBtn.addEventListener('click', broadCastMessage);
    messageForm.addEventListener('submit', broadCastMessage);

    updateScrollPosition();
    Echo.join('chat_channel').here(handleHereUsers)
        .joining(handleUserJoining)
        .leaving(handleUserLeaving)
        .listenForWhisper('typing', handleTypingWhisper)
        .listenForWhisper('new-message', handleNewMessage)
        .listenForWhisper('theme-change-request', handleThemeChange)

    function handleThemeChange(event) {
        // console.log(event)
        if (event.theme === 'light') {
            document.querySelector('html').classList.remove('dark');
        } else if (event.theme === 'dark') {
            document.querySelector('html').classList.add('dark');
        }
    }

    // for send new message on websocket
    function broadCastMessage(event) {

        event.preventDefault();
        let message = inputMessage.value.trim();
        //
        if (message.length <= 0) {
            return;
        }

        if (message === 'تم لایت') {

            // console.log(document.getElementsByTagName('html')[0])

            message = 'تم را به صورت شخصی به حالت روشن تغییر داد';
            document.querySelector('html').classList.remove('dark');

            // inputMessage.value = '';
            // return;

        } else if (message === 'تم دارک') {

            message = 'تم را به صورت شخصی به حالت دارک تغییر داد';
            document.querySelector('html').classList.add('dark');

            // inputMessage.value = '';
            // return;

        } else if (message === 'تم لایت همگانی') {

            document.querySelector('html').classList.remove('dark');
            Echo.join('chat_channel')
                .whisper(
                    'theme-change-request',
                    {
                        theme : 'light'
                    }
                );
            message = 'تم را به صورت همگانی به حالت روشن تغییر داد';
            inputMessage.value = '';

            // return;

        } else if (message === 'تم دارک همگانی') {

            document.querySelector('html').classList.add('dark');
            Echo.join('chat_channel')
                .whisper(
                    'theme-change-request', {
                        theme : 'dark'
                    }
                );
            message = 'تم را به صورت همگانی به حالت دارک تغییر داد';
            inputMessage.value = '';

            // return;
        }


        const data = {
            message: message,
            user: {
                avatar: '{{ asset('images/profile_user.png') }}',
                name: '{{ auth()->user()->name }}',
                uuid: '{{ str(auth()->user()->email)->replace('@dick.chat','') }}',
            }
        }

        {{--// send message--}}
        Echo.join('chat_channel')
            .whisper(
                'new-message', data
            )
        // display message to user sender
        chatList.innerHTML += renderMessage(data.user.avatar, 'شما', data.message);
        inputMessage.value = '';
        updateScrollPosition();
    }

    function handleNewMessage(event) {
        // console.log(event);
        chatList.innerHTML += renderMessage(event.user.avatar, event.user.name, event.message);
        updateScrollPosition();
    }

    const typingTimeOutIds = {};

    // set innerText for user element to 'is typing'
    function handleTypingWhisper(event) {

        if (typingTimeOutIds[event.uuid]) {
            clearTimeout(typingTimeOutIds[event.uuid]);
        }

        // for change between status text
        typingTimeOutIds[event.uuid] = setTimeout(
            () => {
                document.getElementById(`online-user-status-${event.uuid}`).innerText = 'آنلاین';
            },
            2000
        )
        // console.log(event)
        document.getElementById(`online-user-status-${event.uuid}`).innerText = 'در حال نوشتن...';
    }

    //// for show user type something
    function handleMessageTypingEvent(event) {
        // we want to send Notify
        // to other users ,that the current user is typing
        Echo.join('chat_channel').whisper('typing', {
            'name': '{{ auth()->user()->name }}',
            'uuid': '{{ str(auth()->user()->email)->replace('@dick.chat','') }}',
            'message': inputMessage.value,
        })
    }

    // for show online users for each online user
    function handleHereUsers(users) {

        // for each user add that user to online list users
        users.forEach(user => addUserToOnlineList(user))
    }

    function handleUserJoining(user) {
        // console.log(user)
        // console.log(renderMessage(user.avatar,user.display_name,'وارد چت شد!' ));
        chatList.innerHTML += renderMessage(user.avatar, user.display_name, 'وارد چت شد!');
        updateScrollPosition();
        addUserToOnlineList(user);
        // for prevent add duplicate user element item
        // const onlineUserElement = document.getElementById(`online-user-wrapper-${user.uuid}`);
        // if(!onlineUserElement){
        //     usersList.innerHTML += renderOnlineUsers(user.avatar, user.display_name, user.uuid)
        // }
        //usersList.innerHTML += renderOnlineUsers(user.avatar, user.display_name, user.uuid)
    }

    function addUserToOnlineList(user) {
        const onlineUserElement = document.getElementById(`online-user-wrapper-${user.uuid}`);
        if (!onlineUserElement) {
            usersList.innerHTML += renderOnlineUsers(user.avatar, user.display_name, user.uuid)
        }
    }

    function handleUserLeaving(user) {
        // console.log(user)
        // console.log(renderMessage(user.avatar, user.display_name, 'وارد چت شد!'));
        chatList.innerHTML += renderMessage(user.avatar, user.display_name, 'از چت خارج شد!')
        updateScrollPosition();
        // when each user leave chatroom the user element remove from online user list section
        const onlineUserElement = document.getElementById(`online-user-wrapper-${user.uuid}`);
        if (onlineUserElement) {
            onlineUserElement.remove();
        }
    }

    function renderMessage(avatar, display_name, message) {

        return `<li  class="flex items-start gap-4">
            <div class="min-w-10 min-h-10 w-10 h-10 bg-secondary-100 dark:bg-secondary-800 rounded-md overflow-hidden">
                <img src="${avatar}" alt="" class="w-full h-full object-center object-cover">
            </div>
            <div class="text-sm flex flex-col gap-1">
                <div class="text-secondary-500 dark:text-secondary-400 flex gap-4">
                    <div class="flex gap-1 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <p>
                            ${display_name}
                        </p>
                    </div>
                </div>
                <p class="text-secondary-700 dark:text-secondary-300 message">
                    ${message}
                </p>
            </div>
        </li>`;
    }


    function renderOnlineUsers(avatar, display_name, uuid) {
        return `<li id="online-user-wrapper-${uuid}" class="flex items-center gap-4 truncate">
                <div class="w-10 h-10 min-w-10 min-h-10 bg-secondary-200 dark:bg-secondary-800 rounded-md overflow-hidden">
                     <img src="${avatar}" alt="" class="w-full h-full object-center object-cover">
                </div>
                <div class="text-sm truncate">
                    <p class="text-secondary-700 dark:text-secondary-300 truncate">
                        ${display_name}
                    </p>
                    <p id="online-user-status-${uuid}" class="text-secondary-500 dark:text-secondary-400 text-xs truncate">
                        آنلاین
                    </p>
                </div>
            </li>`;
    }


    function updateScrollPosition() {
        // for scroll down chat scroll
        chatListWrapper.scrollTop = chatListWrapper.scrollHeight;
    }
</script>
{{--<script>
    const chatListWrapper = document.getElementById('chat-list-wrapper');
    const chatList = document.getElementById('chat-list');
    const usersList = document.getElementById('online-users-list');
    const inputMessage = document.getElementById('input-message');
    const messageForm = document.getElementById('form-message');
    const messageButton = document.getElementById('btn-message');

    inputMessage.addEventListener('keyup', handleMessageInputTypingEvent);
    messageButton.addEventListener('click', broadcastMessage);
    messageForm.addEventListener('submit', broadcastMessage);

    updateScrollPosition();

    Echo.join('lobby')
        .here(handleHereUsers)
        .joining(handleUserJoining)
        .leaving(handleUserLeaving)
        .listenForWhisper('typing', handleTypingWhisper)
        .listenForWhisper('new-message', handleNewMessageWhisper)
        .listenForWhisper('global-theme-changing-request', handleGlobalThemeChangingRequestWhisper)
    ;

    function handleGlobalThemeChangingRequestWhisper(event)
    {
        if(event.theme === 'light')
        {
            document.querySelector('html').classList.remove('dark');
        }
        else if(event.theme === 'dark')
        {
            document.querySelector('html').classList.add('dark');
        }
    }

    function broadcastMessage(event)
    {
        event.preventDefault();

        let message = inputMessage.value.trim();

        if (message.length <= 0)
        {
            return;
        }

        if(message === 'تم لایت')
        {
            message = 'تم را به صورت شخصی به حالت روشن تغییر داد.'
            document.querySelector('html').classList.remove('dark');
        }
        else if (message === 'تم دارک')
        {
            message = 'تم را به صورت شخصی به حالت تیره تغییر داد.'
            document.querySelector('html').classList.add('dark');
        }
        else if (message === 'تم لایت همگانی')
        {
            document.querySelector('html').classList.remove('dark');
            Echo.join('lobby')
                .whisper(
                    'global-theme-changing-request',
                    {
                        theme: 'light'
                    }
                );
            message = 'تم را به صورت همگانی به حالت روشن تغییر داد.'
            inputMessage.value = '';
        }
        else if (message === 'تم دارک همگانی')
        {
            document.querySelector('html').classList.add('dark');
            Echo.join('lobby')
                .whisper(
                    'global-theme-changing-request',
                    {
                        theme: 'dark'
                    }
                );

            message = 'تم را به صورت همگانی به حالت تیره تغییر داد.'
            inputMessage.value = '';
        }

        const data = {
            message: message,
            user   : {
                avatar      : '{{ auth()->user()->avatar }}',
                display_name: '{{ auth()->user()->display_name }}',
                uuid        : '{{ str(auth()->user()->email)->replace('@okkio.chat', '') }}',
            },
        };

        // send message
        Echo.join('lobby')
            .whisper(
                'new-message',
                data
            );

        chatList.innerHTML += renderMessage(data.user.avatar, 'شما', data.message);
        inputMessage.value = '';
        updateScrollPosition();
    }

    function handleNewMessageWhisper(event)
    {
        chatList.innerHTML += renderMessage(event.user.avatar, event.user.display_name, event.message);
        updateScrollPosition();
    }

    const typingTimeOutIds = {};

    function handleTypingWhisper(event)
    {
        if (typingTimeOutIds[event.uuid])
        {
            clearTimeout(typingTimeOutIds[event.uuid]);
        }

        typingTimeOutIds[event.uuid] = setTimeout(
            () =>
            {
                document.getElementById(`online-user-status-${event.uuid}`).innerText = 'آنلاین';
            },
            2000
        );

        document.getElementById(`online-user-status-${event.uuid}`).innerText = 'درحال نوشتن...';
    }

    function handleMessageInputTypingEvent(event)
    {
        Echo.join('lobby')
            .whisper(
                'typing',
                {
                    display_name: '{{ auth()->user()->display_name }}',
                    uuid        : '{{ str(auth()->user()->email)->replace('@okkio.chat', '') }}',
                    message     : inputMessage.value,
                }
            );
    }

    function handleHereUsers(users)
    {
        users.forEach(user => addUserToOnlineList(user));
    }

    function handleUserJoining(user)
    {
        chatList.innerHTML += renderMessage(user.avatar, user.display_name, 'وارد تالار شد!');
        updateScrollPosition();

        addUserToOnlineList(user);
    }

    function addUserToOnlineList(user)
    {
        const onlineUserElement = document.getElementById(`online-user-wrapper-${user.uuid}`);

        if (!onlineUserElement)
            usersList.innerHTML += renderOnlineUsers(user.avatar, user.display_name, user.uuid);
    }

    function handleUserLeaving(user)
    {
        chatList.innerHTML += renderMessage(user.avatar, user.display_name, 'از تالار خارج شد!');
        updateScrollPosition();
        const onlineUserElement = document.getElementById(`online-user-wrapper-${user.uuid}`);

        if (onlineUserElement)
        {
            onlineUserElement.remove();
        }
    }

    function renderMessage(avatar, display_name, message)
    {
        return `
        <li class="flex items-start gap-4">
            <div class="min-w-10 min-h-10 w-10 h-10 bg-secondary-100 dark:bg-secondary-800 rounded-md overflow-hidden">
                <img src="${avatar}" alt="" class="w-full h-full object-center object-cover">
            </div>
            <div class="text-sm flex flex-col gap-1">
                <div class="text-secondary-500 dark:text-secondary-400 flex gap-4">
                    <div class="flex gap-1 items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-4 h-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                        </svg>
                        <p>
                            ${display_name}
                        </p>
                    </div>
                </div>
                <p class="text-secondary-700 dark:text-secondary-300 message">
                    ${message}
                </p>
            </div>
        </li>`;
    }

    function renderOnlineUsers(avatar, display_name, uuid)
    {
        return `
            <li id="online-user-wrapper-${uuid}" class="flex items-center gap-4 truncate">
                <div class="w-10 h-10 min-w-10 min-h-10 bg-secondary-200 dark:bg-secondary-800 rounded-md overflow-hidden">
                     <img src="${avatar}" alt="" class="w-full h-full object-center object-cover">
                </div>
                <div class="text-sm truncate">
                    <p class="text-secondary-700 dark:text-secondary-300 truncate">
                        ${display_name}
                    </p>
                    <p id="online-user-status-${uuid}" class="text-secondary-500 dark:text-secondary-400 text-xs truncate">
                        آنلاین
                    </p>
                </div>
            </li>
        `;
    }

    function updateScrollPosition()
    {
        chatListWrapper.scrollTop = chatListWrapper.scrollHeight;
    }
</script>--}}
@endscript
