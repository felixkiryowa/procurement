@extends('layouts.dashboard')

@section('content')

<div class="content-wrapper">
    <!-- Main content -->
    <br>
    <br>
    <br>
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
                        Generate Trial Balance
                      </div>
                      <div class="card-body">
                          <form method="POST" action="{{ url('/account/trialbalance/') }}"
                          id="accountGenerator"
                         role="form" class="panel-heading">
                         @csrf
                             <div class="row all_entries_row">


                                     <div class="col-md-3">
                                         <div class="form-group">
                                             <label for="exampleInputEmail1">From Date</label>
                                             <input type="date" class="form-control" name="statement_start_date"
                                             id="ledger_start_date"
                                             placeholder="Enter From Date" required>
                                             <span class="invalid-feedback" id="statementStartDateError"
                                             style="display: none;"></span>
                                         </div>
                                     </div>
                                     <div class="col-md-3">
                                         <div class="form-group">
                                             <label for="exampleInputEmail1">To Date</label>
                                             <input type="date" class="form-control" name="statement_end_date"
                                             id="ledger_end_date"
                                             placeholder="Enter To Date" required>
                                             <span class="invalid-feedback" id="statementEndDateError"
                                             style="display: none;"></span>
                                         </div>
                                     </div>
                                     <div class="col-md-3">
                                        <button type="submit" name="action" class="btn btn-success btn-sm journal-search-btn" value="normal">Generate</button>
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
  <br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br><br>
  @include('inc.copyright')
@endsection
