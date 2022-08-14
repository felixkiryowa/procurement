
@extends('layouts.dashboard', ['title' => 'Balance Sheet'])
<?php

$getBal = new App\Models\AccountBalances();
$getOpenBal = new App\Models\OpenningAccountBalances();

?>
@section('content')
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <br><br><br>
        @include('inc.contentheader', ['title' => 'Welcome' ])

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
                                  <br>
                                  <div class="panel-heading print_button">

                                  </div>
                                  <br>
                                  <div class="card-body">
                                      <div class="row">
                                          <div class="col-12 col-sm-12 assets_container">
                                            <table width="100%" border="0" class="table table-bordered table-striped">
                                                <tr>
                                                  <td width="50%"><strong>CLASS 1: REVENUE</strong></td>
                                                  <td width="50%"><strong> CLASS 2 : EXPENSES</strong></td>
                                                </tr>
                                                <tr>
                                                  <td><?php $overall = 0; $totalrev1 = $totalrev2 = $totalrev3 = 0; $rev1 = 0; $rev2 = 0; $rev3 = 0; ?>
                                                    @foreach ($account_group_revenue as $asset_group_infor )
                                                    <table width="100%" border="0">
                                                      @foreach ($asset_group_infor->ledger_categories as $ledger_category)
                                                      <tr>
                                                        <td colspan="2" ><br><h6><b>{{ $ledger_category->name }}</b></h6></td>
                                                      </tr>
                                                      @if (count($ledger_category->bank_accounts) != 0)

                                                      @foreach ($ledger_category->bank_accounts as $bank_account)
                                                      <?php $exp = substr($bank_account->account_number, 0, 3);

                                                      //var_dump($exp);

                                                      $revlevel = $exp;

                                                       ?>
                                                       @if('133' == $revlevel)
                                                       <?php

                                                       $totalrev = 0;


                                                        $rev1 = $rev1 + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);

                                                        $totalrev = $rev1;


                                                       ?>

                                                       @elseif('142' == $revlevel)
                                                       <?php

                                                       $totalrev2 = 0;

                                                       $rev2 = $rev2 + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);

                                                       $totalrev2 = $rev2;


                                                       ?>

                                                        @elseif('145' == $revlevel)
                                                        <?php

                                                        $totalrev3 = 0;

                                                        $rev3 = $rev3 + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);

                                                        $totalrev3 = $rev3;


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
                                                                              $bank_account->id, 1, $start_date)) }}</td>
                                                      </tr>
                                                      @endforeach

                                                      @endif
                                                      @endforeach
                                                    </table>
                                                    @endforeach</td>
                                                  <td><?php $overall = 0; $totalexp1 = $totalexp2 = $totalexp3 = $totalexp4 = 0; $exp1 = 0; $exp2 = 0; $exp3 = 0; $exp4 = 0; ?>
                                                    @foreach ($account_group_expenses as $asset_group_infor )
                                                    <table width="100%" border="0">
                                                      @foreach ($asset_group_infor->ledger_categories as $ledger_category)
                                                      <tr>
                                                        <td colspan="2" ><br><h6><b>{{ $ledger_category->name }}</b></h6></td>
                                                      </tr>
                                                      @if (count($ledger_category->bank_accounts) != 0)

                                                      @foreach ($ledger_category->bank_accounts as $bank_account)
                                                      <?php $expl = substr($bank_account->account_number, 0, 2);

                                                      //var_dump($exp);

                                                      $explevel = $expl;

                                                       ?>
                                                       @if('21' == $explevel)
                                                       <?php

                                                       $totalexp1 = 0;


                                                        $exp1 = $exp1 + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);

                                                        $totalexp1 = $exp1;


                                                       ?>

                                                       @elseif('22' == $explevel)
                                                       <?php

                                                       $totalexp2 = 0;

                                                       $exp2 = $exp2 + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);

                                                       $totalexp2 = $exp2;


                                                       ?>

                                                        @elseif('26' == $explevel)
                                                        <?php

                                                        $totalexp3 = 0;

                                                        $exp3 = $exp3 + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);

                                                        $totalexp3 = $exp3;


                                                        ?>

                                                        @elseif('28' == $explevel)
                                                            <?php

                                                            $totalexp4 = 0;

                                                            $exp4 = $exp4 + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                                $bank_account->id, 1, $start_date);

                                                            $totalexp4 = $exp4;


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
                                                                              $bank_account->id, 1, $start_date)) }}</td>
                                                      </tr>
                                                      @endforeach

                                                      @endif
                                                      @endforeach
                                                    </table>
                                                    @endforeach</td>
                                                </tr>
                                                <tr>
                                                  <td><b>Total Revenue: {{ number_format($totalrev1 + $totalrev2 + $totalrev3) }}</b></td>
                                                  <td><b>Total Expenses: {{ number_format($totalexp1 + $totalexp2 + $totalexp3 + $totalexp4) }}</b></td>
                                                </tr>
                                                <tr>
                                                  <td></td>
                                                  <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td><strong>CLASS 3 : ASSETS</strong></td>
                                                  <td><strong>CLASS 4 : LIABILITIES</strong></td>
                                                </tr>
                                                <tr>
                                                  <td>

                                                  </td>
                                                  <td>
                                                    <?php $overall_lia = 0; $total_lia_current = 0; $total_lia_non = 0;  ?>
                                                  @foreach ($account_group_liabilities as $asset_group_infor )
                                                  <table width="100%" border="0">
                                                    @foreach ($asset_group_infor->ledger_categories as $ledger_category)
                                                    <tr>
                                                      <td colspan="2"><br><h6><b>{{ $ledger_category->name }}</b></h6></td>
                                                    </tr>
                                                    @if (count($ledger_category->bank_accounts) != 0)
                                                    @foreach ($ledger_category->bank_accounts as $bank_account)
                                                    <?php $lia_exp = substr($bank_account->account_number, 0, 2);

                                                    //var_dump($lia_exp);

                                                    $lialevel = $lia_exp;

                                                     ?>
                                                     @if('41' == $lialevel)
                                                     <?php

                                                     $total_lia = 0;


                                                      $total_lia_current = $total_lia_current + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);

                                                      $total_lia = $total_lia_current;


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
                                                                              $bank_account->id, 1, $start_date)) }}</td>
                                                    </tr>
                                                    @endforeach

                                                    @endif
                                                    @endforeach
                                                  </table>
                                                  @endforeach
                                                  </td>
                                                </tr>
                                                <tr>
                                                  <td><b>Total Assets: {{ number_format($totalass2 + $totalass1) }}</b></td>
                                                  <td><b>Total Liabilities: {{ number_format($total_lia) }}</b></td>
                                                </tr>
                                                <tr>
                                                  <td>&nbsp;</td>
                                                  <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td><strong> CLASS 5 : RESERVES</strong></td>
                                                  <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td>
                                                    <?php $overall_equity = 0; $total_owners = 0; $total_ownerwquity = 0;?>
                                                  @foreach ($account_group_equity as $asset_group_infor )
                                                  <table width="100%" border="0">
                                                    @foreach ($asset_group_infor->ledger_categories as $ledger_category)
                                                    <tr>
                                                      <td colspan="2"><br><h5><b>{{ $ledger_category->name }}</b></h5></td>
                                                    </tr>
                                                    @if (count($ledger_category->bank_accounts) != 0)
                                                    @foreach ($ledger_category->bank_accounts as $bank_account)
                                                    <?php $equi_exp = substr($bank_account->account_number, 0, 2);

                                                    //var_dump($lia_exp);

                                                    $equilevel = $equi_exp;

                                                     ?>
                                                     @if('51' == $equilevel)
                                                     <?php

                                                     $total_ownerwquity = 0;


                                                      $total_owners = $total_owners + $getBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                            $bank_account->id, 1, $start_date, $end_date) + $getOpenBal->calculate_amount_on_accounts($bank_account->account_number,
                                                                              $bank_account->id, 1, $start_date);

                                                      $total_ownerwquity = $total_owners;


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
                                                    @endforeach

                                                    @endif
                                                    @endforeach
                                                  </table>
                                                  @endforeach
                                                  </td>
                                                  <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                  <td><b>Total Equity: {{ number_format($total_ownerwquity) }}</b></td>
                                                  <td>&nbsp;</td>
                                                </tr>
                                              </table>
                                            <br><br>


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
