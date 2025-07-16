<x-app
    title="Advertise your developer-centric SaaS or product"
>
    <div class="container md:text-xl xl:max-w-(--breakpoint-lg)">
        <div class="text-center">
            <img src="{{ Vite::asset('resources/img/icons/megaphone.png') }}" class="mx-auto mb-8 h-24 md:h-28 lg:h-32" />

            <h1 class="text-3xl font-medium tracking-tight text-black lg:text-4xl xl:text-7xl">
                Advertise to <span class="text-blue-600">{{ Number::format($visitors) }}</span>&nbsp;developers
            </h1>

            <p class="mt-3 text-lg text-gray-800 md:mt-4 md:text-xl lg:text-2xl">
                This is the right place to show off your product.
            </p>

            <x-btn
                primary
                size="md"
                href="mailto:hello@benjamincrozat.com"
                class="table mx-auto mt-8 lg:mt-12"
            >
                Get in touch
            </x-btn>
        </div>
    </div>

    <div class="mt-24 text-center">
        <p class="text-sm font-bold tracking-widest text-center text-black uppercase">Trusted by</p>

        <div class="flex flex-wrap gap-y-4 gap-x-8 justify-center items-center px-4 mt-5 md:gap-x-12 lg:gap-x-16">
            <x-icon-kinsta class="flex-none h-[1.15rem] sm:h-6" />

            <div class="text-2xl font-bold text-red-600 sm:text-3xl">larajobs</div>

            {{-- Meilisearch --}}
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 162 25" class="flex-none h-6 translate-y-px sm:h-7">
                <path fill="black" d="M59.207 14.207c0-1.61.772-2.557 2.249-2.557 1.389 0 1.852.992 1.852 2.27v6.858h3.131v-7.21c0-2.69-1.411-4.608-4.19-4.608-1.653 0-2.822.507-3.836 1.631-.661-1.014-1.786-1.631-3.351-1.631-1.654 0-2.8.683-3.33 1.675v-1.41h-2.888v11.553h3.13v-6.637c0-1.543.795-2.491 2.25-2.491 1.389 0 1.852.992 1.852 2.27v6.858h3.13v-6.57ZM79.29 15.905s.045-.419.045-.926c0-3.417-2.316-6.02-5.733-6.02-3.418 0-5.8 2.603-5.8 6.02 0 3.55 2.404 6.064 5.822 6.064 2.668 0 4.785-1.61 5.468-3.947h-3.153c-.375.838-1.279 1.257-2.227 1.257-1.566 0-2.58-.86-2.8-2.448h8.378Zm-5.71-4.432c1.455 0 2.403.882 2.624 2.183h-5.248c.264-1.323 1.19-2.183 2.624-2.183ZM79.796 11.914h1.39v8.864h3.13V9.224h-4.52v2.69Zm2.955-4.189c1.102 0 1.896-.772 1.896-1.874 0-1.103-.794-1.896-1.896-1.896-1.103 0-1.896.793-1.896 1.896 0 1.102.793 1.874 1.896 1.874ZM90.8 18.088c-.089 0-.22.022-.42.022-.705 0-.793-.33-.793-.816V4.241h-3.131v13.274c0 2.293.882 3.307 3.373 3.307.42 0 .816-.044.97-.066v-2.668ZM91.156 11.914h1.389v8.864h3.131V9.224h-4.52v2.69Zm2.954-4.189c1.103 0 1.897-.772 1.897-1.874 0-1.103-.794-1.896-1.897-1.896-1.102 0-1.896.793-1.896 1.896 0 1.102.794 1.874 1.897 1.874ZM101.96 20.899c3.043 0 4.476-1.61 4.476-3.307 0-4.719-6.945-2.095-6.945-5.204 0-1.014.86-1.874 2.602-1.874 1.786 0 2.623.97 2.756 2.183h1.565c-.132-1.522-1.168-3.55-4.277-3.55-2.668 0-4.168 1.587-4.168 3.307 0 4.608 6.946 1.962 6.946 5.182 0 1.124-1.058 1.896-2.955 1.896-1.94 0-2.91-.97-3.02-2.403h-1.588c.132 1.962 1.367 3.77 4.608 3.77ZM118.87 15.475s.022-.353.022-.595c0-3.176-2.05-5.733-5.402-5.733-3.374 0-5.512 2.756-5.512 5.865 0 3.153 1.984 5.887 5.534 5.887 2.668 0 4.52-1.632 5.182-3.77h-1.632c-.485 1.367-1.808 2.359-3.528 2.359-2.359 0-3.836-1.742-3.991-4.013h9.327Zm-5.38-4.917c2.205 0 3.638 1.477 3.837 3.638h-7.74c.265-2.073 1.72-3.638 3.903-3.638ZM128.611 15.299v1.058c0 1.918-1.389 3.241-4.057 3.241-1.654 0-2.558-.683-2.558-2.16 0-.75.353-1.324.904-1.632.573-.31 1.345-.507 5.711-.507Zm-4.234 5.6c1.941 0 3.506-.617 4.3-1.896v1.631h1.499v-7.342c0-2.492-1.433-4.145-4.674-4.145-3.109 0-4.476 1.565-4.763 3.572h1.522c.308-1.588 1.521-2.227 3.175-2.227 2.116 0 3.175.882 3.175 2.778v.728c-3.55 0-5.027.066-6.13.507-1.279.507-2.028 1.631-2.028 2.976 0 1.963 1.256 3.418 3.924 3.418ZM138.735 9.323s-.264-.022-.375-.022c-2.072 0-3.175 1.08-3.594 1.852V9.411h-1.499v11.223h1.565v-6.372c0-2.337 1.434-3.484 3.352-3.484.287 0 .551.022.551.022V9.323ZM139.003 15.034c0 3.065 2.116 5.865 5.534 5.865 3.043 0 4.807-2.029 5.248-4.256h-1.588c-.463 1.742-1.742 2.845-3.66 2.845-2.359 0-3.947-1.874-3.947-4.454 0-2.602 1.588-4.476 3.947-4.476 1.918 0 3.197 1.102 3.66 2.844h1.588c-.441-2.227-2.205-4.255-5.248-4.255-3.418 0-5.534 2.8-5.534 5.887ZM153.47 4.097h-1.565v16.537h1.565v-6.636c0-2.293 1.477-3.484 3.374-3.484 2.006 0 2.954 1.235 2.954 3.263v6.857h1.566v-7.166c0-2.491-1.478-4.321-4.256-4.321-2.094 0-3.241 1.146-3.638 1.83v-6.88Z"/><path fill="url(#a)" d="M0 24.497 7.603 5.045A7.147 7.147 0 0 1 14.259.5h4.584L11.24 19.952a7.147 7.147 0 0 1-6.657 4.545H0Z"/><path fill="url(#b)" d="m11.153 24.497 7.603-19.452A7.147 7.147 0 0 1 25.412.5h4.584l-7.603 19.452a7.147 7.147 0 0 1-6.656 4.545h-4.584Z"/><path fill="url(#c)" d="M22.307 24.497 29.91 5.045A7.147 7.147 0 0 1 36.566.5h4.584l-7.603 19.452a7.147 7.147 0 0 1-6.656 4.545h-4.584Z"/><defs><linearGradient id="a" x1="41.15" x2="0" y1="-1.333" y2="21.915" gradientUnits="userSpaceOnUse"><stop stop-color="#FF5CAA"/><stop offset="1" stop-color="#FF4E62"/></linearGradient><linearGradient id="b" x1="41.15" x2="0" y1="-1.333" y2="21.915" gradientUnits="userSpaceOnUse"><stop stop-color="#FF5CAA"/><stop offset="1" stop-color="#FF4E62"/></linearGradient><linearGradient id="c" x1="41.15" x2="0" y1="-1.333" y2="21.915" gradientUnits="userSpaceOnUse"><stop stop-color="#FF5CAA"/><stop offset="1" stop-color="#FF4E62"/></linearGradient></defs>
            </svg>

            {{-- Sevalla --}}
            <x-icon-sevalla class="flex-none h-9 sm:h-10" />
        </div>
    </div>

    <div class="container md:text-xl xl:max-w-(--breakpoint-lg)">
        <div class="mt-24 text-center">
            <h2 class="text-sm font-bold tracking-widest text-center text-black uppercase md:text-base lg:text-lg">
                The past 30 days on my blog
            </h2>

            <div class="grid grid-cols-2 gap-2 mt-6">
                <div class="p-2 text-black bg-gray-50 rounded-xl md:p-4">
                    <x-heroicon-o-user class="mx-auto mb-2 h-8 text-gray-600 md:h-10 lg:h-12" />
                    <div class="text-3xl font-medium md:text-5xl">{{ Number::format($visitors) }}</div>
                    <div class="md:text-xl lg:text-xl">visitors</div>
                </div>

                <div class="p-2 text-black bg-gray-50 rounded-xl md:p-4">
                    <x-heroicon-o-window class="mx-auto mb-2 h-8 text-gray-600 md:h-10 lg:h-12" />
                    <div class="text-3xl font-medium md:text-5xl">{{ $views }}</div>
                    <div class="md:text-xl lg:text-xl">page views</div>
                </div>

                <div class="p-2 text-black bg-gray-50 rounded-xl md:p-4">
                    <x-heroicon-o-user-group class="mx-auto mb-2 h-8 text-gray-600 md:h-10 lg:h-12" />
                    <div class="text-3xl font-medium md:text-5xl">{{ $sessions }}</div>
                    <div class="md:text-xl lg:text-xl">sessions</div>
                </div>

                <div class="p-2 text-black bg-gray-50 rounded-xl md:p-4">
                    <x-heroicon-o-computer-desktop class="mx-auto mb-2 h-8 text-gray-600 md:h-10 lg:h-12" />
                    <div class="text-3xl font-medium md:text-5xl">{{ $desktop }}%</div>
                    <div class="md:text-xl lg:text-xl">on desktop</div>
                </div>
            </div>
        </div>
    </div>
</x-app>
