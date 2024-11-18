@extends('layout.master')

@push('plugin-styles')
  <link href="{{ asset('assets/plugins/jquery-steps/jquery.steps.css') }}" rel="stylesheet" />
@endpush

@section('content')

<div class="row">
  <div class="col-md-12 grid-margin stretch-card">
    <div class="card">

    </div>
  </div>
</div>
@endsection

<!-- @push('plugin-scripts')
  <script src="{{ asset('assets/plugins/jquery-steps/jquery.steps.min.js') }}"></script>
@endpush -->

@push('custom-scripts')
  <script src="{{ asset('assets/js/wizard.js') }}"></script>
@endpush
