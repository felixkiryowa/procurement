@extends('layouts.dashboard')

@section('content')

<div class="content-wrapper">
    <!-- Main content -->
    <br><br><br>
    @include('inc.contentheader', ['title' => 'Generate Cashbook' ])
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
                      </div>
                      <div class="card-body">
                          <form method="POST" action="{{ url('/account/generate/statement') }}"
                          id="accountGenerator"
                         role="form" class="panel-heading">
                         @csrf
                             <div class="row all_entries_row">
                                    <div class="col-md-3">
                                      <div class="form-group">
                                          <label for="exampleInputPassword1">Account Number</label>
                                          <br>
                                          <select
                                              class="form-control custom-form-inputs select2"
                                              name="account_number"
                                              id="debit_account"
                                              required
                                              >
                                              <option value="">--Select Account Name --</option>
                                              @foreach($all_accounts as $key => $value)
                                              <option data-subtext="<?php print_r($value['account_number']);?>" value="<?php print_r($value['account_number']);?>"> <?php print_r($value['name']);?>  - <?php print_r($value['account_number']);?></option>
                                              @endforeach
                                              </select>
                                          <span class="invalid-feedback" id="statementClientError"
                                          style="display: none;"></span>
                                      </div>
                                    </div>
                                     <div class="col-md-3">
                                         <div class="form-group">
                                             <label for="exampleInputEmail1">From Date</label>
                                             <input type="date" class="form-control" name="statement_start_date"
                                             id="ledger_start_date"
                                             placeholder="Enter From Date">
                                             <span class="invalid-feedback" id="statementStartDateError"
                                             style="display: none;"></span>
                                         </div>
                                     </div>
                                     <div class="col-md-3">
                                         <div class="form-group">
                                             <label for="exampleInputEmail1">To Date</label>
                                             <input type="date" class="form-control" name="statement_end_date"
                                             id="ledger_end_date"
                                             placeholder="Enter To Date">
                                             <span class="invalid-feedback" id="statementEndDateError"
                                             style="display: none;"></span>
                                         </div>
                                     </div>
                                     <div class="col-md-3">
                                        <button type="submit" name="action" class="btn btn-success btn-sm journal-search-btn" value="normal">Data</button>
                                        <button type="submit" name="action" class="btn btn-success btn-sm journal-search-btn" value="excel">Excel</button>
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

  @include('inc.copyright')
@endsection
