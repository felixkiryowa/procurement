@extends('layouts.dashboard')

@section('content')

<div class="content-wrapper">
    <!-- Main content -->
    <br><br><br>
        @include('inc.contentheader', ['title' => 'Cash Book' ])
    <br>
    <br>

    <section class="content">
          <div class="container-fluid">
            <!-- /.row -->
            <!-- Main row -->
            <div class="">
              <!-- Left col -->
              <div class="">
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
                                           <button type="submit" name="action" class="btn btn-success btn-sm journal-search-btn" value="normal">Data</button>
                                           <button type="submit" name="action" class="btn btn-success btn-sm journal-search-btn" value="excel">Excel</button>
                                       </div>
                                   </div>
                            </form>


                        </div>
                      </div>
                </section>
                <!-- /.Left col -->
                <br><br>
              </div>

              <section class="content">
                <div id="loadingDiv" class="loader"></div>
                <div class="container-fluid">
                  <!-- /.row -->
                  <!-- Main row -->
                  <div class="row">
                    <!-- Left col -->
                    <section class="col-lg-12 connectedSortable">
                        <div class="card">
                            <div class="card-header">
                                Account Name: <?php
                                $get_account = DB::table('bank_accounts')
                                                                        ->select('bank_accounts.*')
                                                                        ->where('account_number',  $account)
                                                                        ->first();

                                                            $get_sub_accounth = DB::table('sub_bank_accounts')
                                                                        ->select('sub_bank_accounts.*')
                                                                        ->where('account_number',  $account)
                                                                        ->first();

                                                            $get_more_accountsh = DB::table('more_account_belows')
                                                                        ->select('more_account_belows.*')
                                                                        ->where('account_number',  $account)
                                                                        ->first();

                                                            $get_last_accountsh = DB::table('last_bank_accounts')
                                                                        ->select('last_bank_accounts.*')
                                                                        ->where('account_number',  $account)
                                                                        ->first();

                                                            if(!empty($get_accounth->name)){
                                                            echo $get_accounth->name;
                                                            }

                                                            if(!empty($get_sub_accounth->name)){
                                                            echo $get_sub_accounth->name;
                                                            }

                                                            if(!empty($get_more_accountsh->name)){
                                                            echo $get_more_accountsh->name;
                                                            }

                                                            if(!empty($get_last_accountsh->name)){
                                                            echo $get_last_accountsh->name;
                                                            }
                                ?>
                            </div>
                            <!-- /.card-header -->
                            <div class="card-body">
                                    <div class="card card-primary">
                                        <div class="card-header">
                                        </div>
                                        <!-- /.card-header -->
                                        <hr>
                                        <br>
                                        <div class="table-responsive" id="default_new_ledgers">
                                            <table id="" class="table table-bordered table-striped">
                                                <thead>
                                                    <tr>
                                                        <th>Date</th>
                                                        <th>Account Name</th>
                                                        <th>Description</th>

                                                        <th>Received Income</th>
                                                        <th>Payment (Expenses)</th>
                                                        <th>Running Balance</th>
                                                      </tr>
                                                </thead>
                                                <tbody>
                                                         <tr style="color: red;">
                                                            <td>{{ date('F d, Y',strtotime($start_date)) }}</td>
                                                            <td>Bal B/F</td>
                                                            <td></td>
                                                            <td></td>
                                                            <td></td>
                                                            <td><?php $totalBal = $Prev_amount_on_account; ?>{{ number_format($Prev_amount_on_account) }}</td>
                                                         </tr>
                                                    @foreach($account_journal_entries as $entry)
                                                        <tr>
                                                            <td>{{ date('F d, Y',strtotime($entry->created_at)) }}</td>
                                                            <td>
                                                                <?php

                                                            $entry_account = DB::table('general_ledgers')
                                                                        ->select('general_ledgers.*')
                                                                        ->where('general_ledgers.reference', $entry->reference)
                                                                        ->where('general_ledgers.account', '!=', $entry->account)
                                                                        ->first();

                                                            $get_account = DB::table('bank_accounts')
                                                                        ->select('bank_accounts.*')
                                                                        ->where('account_number',  $entry_account->account)
                                                                        ->first();

                                                            $get_sub_account = DB::table('sub_bank_accounts')
                                                                        ->select('sub_bank_accounts.*')
                                                                        ->where('account_number',  $entry_account->account)
                                                                        ->first();

                                                            $get_more_accounts = DB::table('more_account_belows')
                                                                        ->select('more_account_belows.*')
                                                                        ->where('account_number',  $entry_account->account)
                                                                        ->first();

                                                            $get_last_accounts = DB::table('last_bank_accounts')
                                                                        ->select('last_bank_accounts.*')
                                                                        ->where('account_number',  $entry_account->account)
                                                                        ->first();

                                                            if(!empty($get_account->name)){
                                                            echo $get_account->name;
                                                            }

                                                            if(!empty($get_sub_account->name)){
                                                            echo $get_sub_account->name;
                                                            }

                                                            if(!empty($get_more_accounts->name)){
                                                            echo $get_more_accounts->name;
                                                            }

                                                            if(!empty($get_last_accounts->name)){
                                                            echo $get_last_accounts->name;
                                                            }

                                                                ?>
                                                            </td>
                                                            <td>{{ $entry->details }}</td>

                                                            <td>
                                                                @if ($entry->debit == null)

                                                                @else
                                                                    {{ number_format($entry->debit) }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                @if ($entry->credit == null)

                                                                @else
                                                                    {{ number_format($entry->credit) }}
                                                                @endif
                                                            </td>
                                                            <td>
                                                                <?php
                                                                    if($entry->debit == null){
                                                                      $totalBal = $totalBal - $entry->credit ;
                                                                    }else{
                                                                         $totalBal = $totalBal + $entry->debit ;
                                                                    }

                                                                    echo number_format($totalBal);
                                                                ?>
                                                            </td>

                                                        </tr>
                                                        <?php $totalBal = $totalBal; ?>
                                                    @endforeach
                                                    <tr style="color: red;">
                                                        <td>{{ date('F d, Y',strtotime($end_date)) }}</td>
                                                        <td>Closing Bal</td>
                                                        <td></td>
                                                        <td></td>
                                                        <td></td>
                                                        <td>{{ number_format( $amount_on_account ) }}</td>
                                                     </tr>
                                                    </tbody>
                                              </table>
                                        </div>
                                    </div>
                            </div>
                            <!-- /.card-body -->
                          </div>
                    </section>
                    <!-- /.Left col -->
                  </div>
                  <!-- /.row (main row) -->
                </div><!-- /.container-fluid -->
              </section>

        <!-- /.content -->
  </div></div>

  @include('inc.copyright')
@endsection
