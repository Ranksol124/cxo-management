<div class="p-4">
    <h2 class="font-bold text-lg date-color">{{ $getRecord()->title }}</h2>
    <p><strong>Company:</strong> {{ $getRecord()->company }}</p>
    <p><strong>Job Link:</strong> <a href="{{ $getRecord()->link }}" target="_blank" class="">{{ $getRecord()->link }}</a></p>
    <p><strong>Posted On: </strong><span class="date-color">{{ $getRecord()->created_at->format('d M Y') }}</span></p>
    <p><strong>Date: </strong> <span class="date-color">{{ $getRecord()->due_date ? \Carbon\Carbon::parse($getRecord()->due_date)->format('d M Y') : '' }}</span></p>
</div>