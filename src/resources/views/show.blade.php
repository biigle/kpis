@extends('admin.base')


@push('scripts')
    <script type="text/javascript"></script>
@endpush

@section('admin-content')
    <div class="row-sm-6">
        <div class="panel body">
            <div class="btn-group-lg" role="group">
                @foreach ($monthOverview as $month)
                    <a id="btn"
                        href="{{ substr(URL::current(), 0, strrpos(URL::current(), 'kpis/') + 5) . "$loop->iteration" }}"
                        class="btn btn-default @if ($idx == $loop->iteration) active @endif">{{ $month }}</a>
                @endforeach
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Actions
                </h3>
            </div>
            <div class="panel-body">
                <p class="h1 text-center">
                    {{ $actions }}
                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Visits
                </h3>
            </div>
            <div class="panel-body">
                <p class="h1 text-center">
                    {{ $visits }}
                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Unique user
                </h3>
            </div>
            <div class="panel-body">
                <p class="h1 text-center">
                    {{ $uuserNbr }}
                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    User
                </h3>
            </div>
            <div class="panel-body">
                <p class="h1 text-center">
                    {{ $userNbr }}
                </p>
            </div>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title">
                    Storage usage
                </h3>
            </div>
            <div class="panel-body">
                <p class="h1 text-center">
                    {{ $storage }}
                </p>
            </div>
        </div>
    </div>
@endsection
