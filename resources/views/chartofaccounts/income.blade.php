
@extends('layouts.dashboard', ['title' => 'Income Statement'])
<?php

$getBal = new App\Models\AccountBalances();
$getOpenBal = new App\Models\OpenningAccountBalances();

?>
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <br><br>
      @include('inc.contentheader', ['title' => 'Income Statement' ])
      <!-- Main content -->
      <section class="content">
            <div class="container-fluid">
              <!-- Small boxes (Stat box) -->
              <!-- /.row -->
              <!-- Main row -->
              <div class="row">
                <!-- Left col -->
                <section class="col-lg-12 connectedSortable">
                    <div class="card card-primary">
                        <div class="card-header">
                            <b>Start:</b> {{ date('jS F Y',strtotime($start_date)) }} <b>End:</b>  {{ date('jS F Y',strtotime($end_date))  }}
                        </div>
                        <!-- /.card-header -->
                        <div class="card-body">

                            <div class="row">
                              <div class="col-md-12">

                                  <div class="card-body">
                                      <div class="row">
                                          <div class="col-12 col-sm-12 assets_container">
                                            <table width="100%" border="0" class="table table-bordered table-striped">
                                                <tr>
                                                  <td width="">
                                                    <h2>Revenue</h2>
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td><?php $total_rev = 0; $overall_rev = 0; $total_op_rev = 0; $total_op_non = 0; $total_op_non1 = 0;  ?>

                                                    @foreach ($account_group_revenue as $asset_group_infor )
                                                  <table width="100%" border="0">
                                                    @foreach ($asset_group_infor->ledger_categories as $ledger_category)
                                                    <tr>
                                                      <td colspan="2"><br><h6><b>{{ $ledger_category->name }}</b></h6></td>
                                                    </tr>
                                                    @if (count($ledger_category->bank_accounts) != 0)
                                                    @foreach ($ledger_category->bank_accounts as $bank_account)
                                                    <?php if
                                                                            (number_format($getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date)) != 0) { ?>
                                                    <?php $rev_exp = substr($bank_account->account_number, 0, 3);

                                                    //var_dump($lia_exp);

                                                    $revlevel = $rev_exp

                                                     ?>
                                                     @if('133' == $revlevel)
                                                     <?php

                                                     $total_rev = 0;


                                                      $total_op_rev = $total_op_rev + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);

                                                      $total_rev = $total_op_rev;


                                                     ?>

                                                     @elseif('142' == $revlevel)
                                                     <?php

                                                     $total_rev = 0;

                                                     $total_op_non = $total_op_non + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);

                                                     $total_rev = $total_op_non;


                                                     ?>
                                                     @elseif('145' == $revlevel)
                                                     <?php

                                                     $total_rev = 0;

                                                     $total_op_non1 = $total_op_non1 + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);

                                                     $total_rev = $total_op_non1;


                                                     ?>
                                                     @endif
                                                    <tr>
                                                      <td width="50%">
                                                        <?php
                                                            $sub_accounts = DB::table('sub_bank_accounts')
                                                            ->select('sub_bank_accounts.*')
                                                            ->where('sub_bank_accounts.bank_account_id', $bank_account->id)
                                                            ->get();
                                                            $bank_account_id = Crypt::encrypt($bank_account->id);
                                                        ?>

                                                              @if (!$sub_accounts->isEmpty())
                                                                              {{ $bank_account->name }}
                                                              @else
                                                                              {{ $bank_account->name }}
                                                              @endif
                                                      </td>
                                                      <td>{{ number_format($getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                        $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date)) }} </td>
                                                    </tr>
                                                    <?php } ?>
                                                    @endforeach

                                                    @endif
                                                    @endforeach
                                                  </table>
                                                  @endforeach
                                                </td>
                                                </tr>
                                                <tr>
                                                  <td><b>Total Revenues: {{ number_format($total_rev) }}</b></td>
                                                </tr>
                                                <tr>
                                                  <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td><h2>Expenses</h2></td>
                                                </tr>
                                                <tr>
                                                  <td><?php $overall_exp = 0; $total_exp = 0; $total_pay = 0; $total_rent = 0; $total_non_op = 0; $total_op_main = 0;  ?>
                                                    @foreach ($account_group_expenses as $asset_group_infor )
                                                    <table width="100%" border="0">
                                                      @foreach ($asset_group_infor->ledger_categories as $ledger_category)
                                                      <tr>
                                                        <td colspan="2"><br><h6><b>{{ $ledger_category->name }}</b></h6></td>
                                                      </tr>
                                                      @if (count($ledger_category->bank_accounts) != 0)
                                                      @foreach ($ledger_category->bank_accounts as $bank_account)
                                                      <?php if
                                                                              (number_format($getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date, $end_date)) != 0) { ?>
                                                      <?php $exp_exp = substr($bank_account->account_number, 0, 2);

                                                      //var_dump($lia_exp);

                                                      $explevel = $exp_exp;

                                                       ?>
                                                       @if('21' == $explevel)
                                                       <?php

                                                       $total_exp = 0;


                                                        $total_non_op = $total_non_op + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date);

                                                       $total_exp = $total_non_op;


                                                       ?>

                                                       @elseif('22' == $explevel)
                                                       <?php

                                                       $total_exp = 0;

                                                       $total_pay = $total_pay + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date);

                                                       $total_exp = $total_pay;


                                                       ?>

                                                       @elseif('26' == $explevel)
                                                       <?php

                                                       $total_exp = 0;

                                                       $total_rent = $total_rent + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date);

                                                       $total_exp = $total_rent;


                                                       ?>

                                                       @elseif('28' == $explevel)
                                                       <?php

                                                       $total_exp = 0;

                                                       $total_op_main = $total_op_main + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date);

                                                      $total_exp = $total_op_main;


                                                       ?>
                                                       @endif

                                                      <tr>
                                                        <td width="50%"> <?php
                                                                                 $sub_accounts = DB::table('sub_bank_accounts')
                                                                                ->select('sub_bank_accounts.*')
                                                                                ->where('sub_bank_accounts.bank_account_id', $bank_account->id)
                                                                                ->get();
                                                                                $bank_account_id = Crypt::encrypt($bank_account->id);
                                                                ?>

                                                                @if (!$sub_accounts->isEmpty())
                                                                                {{ $bank_account->name }}
                                                                @else
                                                                                {{ $bank_account->name }}
                                                                @endif
                                                        </td>
                                                        <td>{{ number_format($getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                          $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date)) }} </td>
                                                      </tr>
                                                    <?php }?>
                                                      @endforeach

                                                      @endif
                                                      @endforeach
                                                    </table>
                                                    @endforeach</td>
                                                </tr>
                                                <tr>
                                                  <td><b>Total Expenses: {{ number_format($total_exp) }}</b></td>
                                                </tr>
                                                <tr>
                                                  <td> <h4> Balance:
                                                    <?php
                                                    $final_rev = $total_op_rev + $total_op_non + $total_op_non1;
                                                    $final_exp = $total_non_op + $total_pay + $total_rent + $total_op_main;
                                                    $balance = $final_rev - $final_exp;
                                                    ?>

                                                    {{ number_format($balance)}}
                                                  </h4></td>
                                                </tr>
                                              </table>

                                          </div>
                                        </div>
                                  </div>
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
    </div>
    @include('inc.copyright')
@endsection
