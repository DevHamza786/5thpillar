<x-mail::message>
@if(($lead->plan_type ?? 'hajj') === 'umrah')
# New Umrah Plan Lead

A new Umrah Planner form has been submitted from the website.
@else
# New Hajj Plan Lead

A new Hajj Planner form has been submitted from the website.
@endif

### User Details
- **Name:** {{ $lead->name }}
- **Email:** {{ $lead->email }}
- **Phone:** {{ $lead->phone }}
- **City:** {{ $lead->address ?? 'N/A' }}

### Planner Details
- **Current Age:** {{ $lead->age }} Years
@if(($lead->plan_type ?? 'hajj') === 'umrah')
- **Umrah Plan Term:** {{ $lead->hajj_year }} Years
@else
- **Hajj Plan Term:** {{ $lead->hajj_year }} Years
@endif

### Calculation Results
- **Annual Contribution:** PKR {{ number_format($results['summary']['annual_contribution']) }}
- **Takaful Benefit:** PKR {{ number_format($results['summary']['takaful_benefit']) }}
- **Total Contribution:** PKR {{ number_format($results['totals']['contribution']) }}
- **Estimated Return (9%):** PKR {{ number_format($results['totals']['return_9']) }}
- **Estimated Return (13%):** PKR {{ number_format($results['totals']['return_13']) }}

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
