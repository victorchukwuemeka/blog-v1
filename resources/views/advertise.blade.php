<x-app
    title="Advertise your developer-centric SaaS or product"
>
    <div class="container md:text-xl xl:max-w-(--breakpoint-lg)">
        <h1 class="text-3xl font-medium tracking-tight text-black lg:text-4xl xl:text-7xl">
            <span class="text-blue-600">{{ Number::format($visitors) }}</span> of eye balls<br />
            on your developer-centric product
        </h1>

        <h2 class="mt-12 text-xl font-medium text-black md:mt-16 lg:text-2xl xl:text-3xl">
            The last 30 days
        </h2>

        <p class="mt-4">I'm mostly visited by developers. Below, you will find the metrics that matter to any advertiser. Please <a href="https://benjamincrozat.pirsch.io/?domain=benjamincrozat.com&interval=30d&scale=day" target="_blank" class="font-medium underline">check my analytics dashboard</a> to learn even more about my audience.</p>

        <div class="grid grid-cols-2 gap-2 mt-10">
            <div class="p-4 bg-gray-50 rounded-xl">
                <div class="text-3xl font-medium text-black md:text-5xl lg:text-7xl">{{ Number::format($visitors) }}</div>
                <div class="md:text-xl lg:text-xl">visitors</div>
            </div>

            <div class="p-4 bg-gray-50 rounded-xl">
                <div class="text-3xl font-medium text-black md:text-5xl lg:text-7xl">{{ $views }}</div>
                <div class="md:text-xl lg:text-xl">page views</div>
            </div>

            <div class="p-4 bg-gray-50 rounded-xl">
                <div class="text-3xl font-medium text-black md:text-5xl lg:text-7xl">{{ $sessions }}</div>
                <div class="md:text-xl lg:text-xl">sessions</div>
            </div>

            <div class="p-4 bg-gray-50 rounded-xl">
                <div class="text-3xl font-medium text-black md:text-5xl lg:text-7xl">{{ $desktop }}%</div>
                <div class="md:text-xl lg:text-xl">on desktop</div>
            </div>
        </div>

        <h2 class="mt-12 text-xl font-medium text-black md:mt-16 lg:text-2xl xl:text-3xl">
            Where will your ad be displayed?
        </h2>

        <p class="mt-4">
            Here are the available advertising spots at the moment:
        </p>

        <ul class="mt-1 ml-3 list-disc list-inside">
            <li>
                The top of each page
            </li>

            <li>
                The sidebar on each article
            </li>
        </ul>

        <p class="mt-4">
            Each spot has a cost and you can choose to buy one or both.
        </p>

        <x-btn
            primary
            size="md"
            href="mailto:hello@benjamincrozat.com"
            class="table mx-auto mt-12 md:mt-16"
        >
            Email me
        </x-btn>
    </div>
</x-app>