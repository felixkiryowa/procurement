@extends('layouts.dashboard')

@section('content')
<?php

$prevAmount = new App\Models\GeneralLedger();

?>
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
            <div class="">


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
                                Trial Balance
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
                                            <table width="100%" border="0" class="table table-bordered table-striped">
                                                <tr>
                                                  <td >Item Code</td>
                                                  <td >Description</td>
                                                  <td >Beginning Balance </td>
                                                  <td >Period Activity</td>
                                                  <td >Ending Balance</td>
                                                </tr>
                                                <?php
                                                            $debit1 = 0;
                                                            $debit2 = 0;
                                                            $credit1 = 0;
                                                            $credit2 = 0;
                                                            $credit3 = 0;
                                                            $assets_total = 0;
                                                ?>
                                                @foreach($account_journal_entries as $entry)
                                                <tr>
                                                  <td><table cellspacing="0" cellpadding="0">
                                                    <tr>
                                                      <td width="164">{{ $entry->account }}</td>
                                                    </tr>
                                                  </table></td>
                                                  <td>   <?php

                                                  $get_account = DB::table('bank_accounts')
                                                                        ->select('bank_accounts.*')
                                                                        ->where('account_number',  $entry->account)
                                                                        ->first();

                                                            $get_sub_account = DB::table('sub_bank_accounts')
                                                                        ->select('sub_bank_accounts.*')
                                                                        ->where('account_number',  $entry->account)
                                                                        ->first();

                                                            $get_more_accounts = DB::table('more_account_belows')
                                                                        ->select('more_account_belows.*')
                                                                        ->where('account_number',  $entry->account)
                                                                        ->first();

                                                            $get_last_accounts = DB::table('last_bank_accounts')
                                                                        ->select('last_bank_accounts.*')
                                                                        ->where('account_number',  $entry->account)
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
                                                  ?> </td>
                                                  <td><?php
                                                  echo $prevAmount->getPrevAmount($entry->account, $start_date, $end_date);
                                                  ?></td>
                                                  <td><?php echo $prevAmount->getActivityAmount($entry->account, $start_date, $end_date);
                                                    ?></td>
                                                  <td><?php echo $prevAmount->getClosingAmount($entry->account, $start_date, $end_date);
                                                  ?>
                                                  </td>
                                                </tr>
                                                @endforeach

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
