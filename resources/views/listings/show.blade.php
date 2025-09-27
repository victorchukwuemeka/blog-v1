<x-app
    title="$listing->title"
    description="$listing->description"
>
    <article class="container lg:max-w-(--breakpoint-md)">
        <h1 class="font-medium tracking-tight text-black text-balance text-3xl/none sm:text-4xl/none lg:text-5xl/none">
            {{ $listing->title }}
        </h1>

        <x-prose class="mt-8">
            <h2>About the job</h2>

            <table>
                <tr>
                    <th>Company</th>
                    <td>{{ $listing->company->name }}</td>
                </tr>

                <tr>
                    <th>Salary</th>
                    <td>{{ Number::currency($listing->min_salary, $listing->currency ?? 'USD') }}—{{ Number::currency($listing->max_salary, $listing->currency ?? 'USD') }}</td>
                </tr>

                <tr>
                    <th>Locations</th>
                    <td>{{ collect($listing->locations)->join(', ') }}</td>
                </tr>

                <tr>
                    <th>Setting</th>
                    <td>{{ ucfirst($listing->setting) }}</td>
                </tr>

                <tr>
                    <th>Published on</th>
                    <td>{{ $listing->published_on->diffForHumans() }}</td>
                </tr>
            </table>

            {!! Str::markdown($listing->description) !!}

            <h2>Technologies</h2>

            <ul>
                @foreach ($listing->technologies as $technology)
                    <li>
                        {{ $technology }}
                    </li>
                @endforeach
            </ul>

            <h2>About {{ $listing->company->name }}</h2>

            {!! Str::markdown($listing->company->about) !!}

            <h2>How to apply</h2>

            <ul>
                @foreach ($listing->how_to_apply as $step)
                    <li>{{ $step }}</li>
                @endforeach
            </ul>

            @if (!empty($listing->perks))
                <h2>Perks and benefits</h2>

                <ul>
                    @foreach ($listing->perks as $perk)
                        <li>{{ $perk }}</li>
                    @endforeach
                </ul>
            @endif

            @if (!empty($listing->interview_process))
                <h2>Interview process</h2>

                <ul>
                    @foreach ($listing->interview_process as $step)
                        <li>{{ $step }}</li>
                    @endforeach
                </ul>
            @endif

            <p class="mt-8"><strong>Equity:</strong> {{ $listing->equity ? 'Yes' : 'No' }}</p>

            <div class="text-center not-prose">
                <x-btn primary href="{{ $listing->url }}" target="_blank">
                    Apply now
                </x-btn>
            </div>
        </x-prose>
    </article>
</x-app>
