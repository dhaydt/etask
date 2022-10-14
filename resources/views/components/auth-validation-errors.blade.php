@props(['errors'])

@if ($errors->any())
{{-- <div {{ $attributes }}>
    <ul class="mt-3 list-disc list-inside text-sm text-red-600">
        @foreach ($errors->all() as $error)
        <li>{{ $error }}</li>
        @endforeach
    </ul>
</div> --}}
<div class="toast d-block showing align-items-center text-center ms-auto text-white bg-red-300 border-0 m-2" role="alert" aria-live="assertive" style="opacity: 1 !important"
    aria-atomic="true">
    <div class="d-flex">
        <div class="toast-body">
            @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
            @endforeach
        </div>
    </div>
</div>
<style>
    .bg-red-300{
        background: rgb(246, 131, 131);
    }
</style>
@endif
