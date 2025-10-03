<x-app
    :title="$job->title"
    :description="$job->description"
>
    <article class="container lg:max-w-(--breakpoint-md)">
        <h1 class="font-medium tracking-tight text-black text-balance text-3xl/none sm:text-4xl/none lg:text-5xl/none">
            {{ $job->title }}
        </h1>

        <x-prose class="mt-8">
            <h2>About the job</h2>

            <table>
                <tr>
                    <th>Company</th>
                    <td>{{ $job->company->name }}</td>
                </tr>

                @if ($job->min_salary && $job->max_salary)
                    <tr>
                        <th>Salary</th>
                        <td>{{ Number::currency($job->min_salary, $job->currency ?? 'USD') }}—{{ Number::currency($job->max_salary, $job->currency ?? 'USD') }}</td>
                    </tr>
                @endif

                <tr>
                    <th>Equity</th>
                    <td>{{ $job->equity ? 'Yes' : 'No' }}</td>
                </tr>

                @if (!empty($job->locations))
                    <tr>
                        <th>Locations</th>
                        <td>{{ collect($job->locations)->join(', ') }}</td>
                    </tr>
                @endif

                <tr>
                    <th>Setting</th>
                    <td>{{ ucfirst($job->setting) }}</td>
                </tr>
            </table>

            {!! Str::markdown($job->description) !!}

            @if (! empty($job->technologies))
                <h2>Technologies</h2>

                <ul>
                    @foreach ($job->technologies as $technology)
                        <li>
                            {{ $technology }}
                        </li>
                    @endforeach
                </ul>
            @endif

            <h2>About {{ $job->company->name }}</h2>

            {!! Markdown::parse($job->company->about) !!}

            @if (!empty($job->perks))
                <h2>Perks and benefits</h2>

                <ul>
                    @foreach ($job->perks as $perk)
                        <li>{{ Markdown::parse($perk) }}</li>
                    @endforeach
                </ul>
            @endif

            @if (!empty($job->interview_process))
                <h2>Interview process</h2>

                <ul>
                    @foreach ($job->interview_process as $step)
                        <li>{{ Markdown::parse($step) }}</li>
                    @endforeach
                </ul>
            @endif

            <h2>How to apply</h2>

            <ul>
                @foreach ($job->how_to_apply as $step)
                    <li>{{ Markdown::parse($step) }}</li>
                @endforeach
            </ul>

            <div class="text-center not-prose">
                <x-btn primary href="{{ $job->url }}" target="_blank">
                    Apply now
                </x-btn>
            </div>
        </x-prose>
    </article>

    <script type="application/ld+json">
        {
            "@@context": "https://schema.org/",
            "@@type": "JobPosting",
            "title": @json($job->title),
            "description": @json($job->description),
            "identifier": {
                "@@type": "PropertyValue",
                "name": @json($job->company->name),
                "value": @json((string) $job->id)
            },
            "datePosted": @json(optional($job->created_at)->toIso8601String()),
            "employmentType": "FULL_TIME",
            "hiringOrganization": {
                "@@type": "Organization",
                "name": @json($job->company->name),
                "sameAs": @json($job->company->url),
                "logo": @json($job->company->logo)
            },
            "jobLocationType": @json($job->setting === 'fully-remote' ? 'TELECOMMUTE' : null),
            "jobLocation": {
                "@@type": "Place",
                "name": @json(collect($job->locations)->first())
            },
            "baseSalary": {
                "@@type": "MonetaryAmount",
                "currency": @json($job->currency ?? 'USD'),
                "value": {
                    "@@type": "QuantitativeValue",
                    "minValue": @json($job->min_salary),
                    "maxValue": @json($job->max_salary),
                    "unitText": "YEAR"
                }
            },
            "directApply": false
        }
    </script>
</x-app>
