@extends(backpack_view('blank'))
@section('content')
<div class="animated fadeIn">

    <!-- /.row-->
    <div class="card">
        <div class="card-body">
            <div class="row">
                <div class="col-sm-12">
                <h4 class="card-title mb-0">Pledges this Month</h4>
                <div class="small text-muted">{{ Carbon\Carbon::now()->year }}</div>
                </div>
                <!-- /.col-->
                <div class="col-sm-12 d-none d-md-block">
                    {!! $pledgesChart->container() !!}
                </div>
                <!-- /.col-->
            </div>
            <!-- /.row-->
            <div class="row">

            </div>

        </div>

    </div>

    <!-- /.card-->
@endsection

@section('after_styles')
    <link rel="stylesheet" href="https://backstrap.net/vendors/flag-icon-css/css/flag-icon.min.css">
@endsection

@section('after_scripts')
    <script src="https://backstrap.net/vendors/chart.js/js/Chart.min.js"></script>
    <script src="https://backstrap.net/vendors/@coreui/coreui-plugin-chartjs-custom-tooltips/js/custom-tooltips.min.js"></script>
    <script src="https://backstrap.net/js/main.js"></script>
    {!! $pledgesChart->script() !!}

@endsection
