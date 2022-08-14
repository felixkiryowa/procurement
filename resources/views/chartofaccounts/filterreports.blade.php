
@extends('layouts.dashboard', [ 'title' => 'Generate Trial Balance/Balancesheet/Income Statement'])

@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <br><br><br>
        @include('inc.contentheader', ['title' => 'Generate Reports' ])
      <!-- Main content -->
      <br>
     
      <br>
      <section class="content">
            <div class="container-fluid">
              <!-- /.row -->
              <!-- Main row -->
              <div class="row">
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">
                    <div class="card card-primary">
                        <div class="card-header">
                            Generate Reports
                        </div>
                        <div class="card-body" style="height: 300px;">
                            <form action="{{ url('/account/trialbalance/') }}"
                           role="form" class="panel-heading" method="POST">
                           @csrf
                               <div class="row all_entries_row">
                                      <div class="col-md-3">
                                        <div class="form-group {{ $errors->has('report') ? 'has-error' : ''}}">
                                            <label for="exampleInputPassword1">Select Report</label>
                                            <br>
                                            <select
                                            class="form-control"
                                            name="report"
                                            >
                                                <option value="">-- Select Report --</option>
                                                <option value="Trial Balance">Trial Balance</option>
                                                <option value="Balance Sheet">Balance Sheet</option>
                                                <option value="Income Statement">Income Statement</option>
                                            </select>
                                        {!! $errors->first('report', '<p class="text-danger">:message</p>') !!}
                                        </div>
                                      </div>
                                       <div class="col-md-2">
                                           <div class="form-group {{ $errors->has('statement_start_date') ? 'has-error' : ''}}">
                                               <label for="exampleInputEmail1">From Date</label>
                                               <input type="date" class="form-control" name="statement_start_date" id="ledger_start_date"
                                               placeholder="Enter From Date" required>
                                               {!! $errors->first('statement_start_date', '<p class="text-danger">:message</p>') !!}
                                           </div>
                                       </div>
                                       <div class="col-md-2">
                                           <div class="form-group {{ $errors->has('statement_end_date') ? 'has-error' : ''}}">
                                               <label for="exampleInputEmail1">To Date</label>
                                               <input type="date" class="form-control" name="statement_end_date" id="ledger_end_date"
                                               placeholder="Enter To Date" required>
                                               {!! $errors->first('statement_end_date', '<p class="text-danger">:message</p>') !!}
                                           </div>
                                       </div>
                                       <div class="col-md-3">
                                           <button type="submit" class="btn btn-success btn-sm journal-search-btn">Generate Report</button>
                                       </div>
                                   </div>
                            </form>
                        </div>
                      </div>
                </section>
                <!-- /.Left col -->
              </div>
              <!-- /.row (main row) -->
            </div><!-- /.container-fluid -->
          </section>
          <!-- /.content -->
    </div>
    <br><br><br><br><br><br><br><br><br><br><br><br><br><br>
    @include('inc.copyright')
@endsection
